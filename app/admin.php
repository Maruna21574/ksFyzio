<?php
declare(strict_types=1);

/* Administrácia obsahu (/admin). Volá sa z index.php; používa session s vlastným názvom. */

require_once __DIR__ . '/lib/image.php';
require_once __DIR__ . '/lib/admin_schema.php';
require_once __DIR__ . '/lib/admin_form.php';

/* ---------- Session a prihlásenie ---------- */

function adm_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function adm_start(): void
{
    $dir = storage_dir() . '/sessions';
    if (!is_dir($dir)) {
        @mkdir($dir, 0700, true);
    }
    if (is_dir($dir) && is_writable($dir)) {
        session_save_path($dir);
    }
    ini_set('session.use_strict_mode', '1');
    ini_set('session.gc_maxlifetime', '7200');
    session_name('ksf_admin');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => base_path() . '/admin',
        'secure' => adm_https(),
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
    if (!empty($_SESSION['ok']) && (time() - (int) ($_SESSION['seen'] ?? 0)) > 7200) {
        $_SESSION = [];
    }
    $_SESSION['seen'] = time();
}

function adm_authed(): bool
{
    return !empty($_SESSION['ok']);
}

function adm_csrf(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(24));
}

function adm_csrf_ok(): bool
{
    return is_string($_POST['_csrf'] ?? null) && hash_equals(adm_csrf(), $_POST['_csrf']);
}

function adm_flash(string $type, $msgs): void
{
    $_SESSION['flash'] = [$type, (array) $msgs];
}

function adm_admin_hash(): ?string
{
    $a = storage_read('admin');
    return is_string($a['hash'] ?? null) ? $a['hash'] : null;
}

/** Obmedzenie pokusov o prihlásenie: 5 chýb za 15 minút z jednej IP. */
function adm_throttle_blocked(): bool
{
    $all = storage_read('attempts') ?? [];
    $rec = $all[hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '')] ?? null;
    return $rec && $rec['n'] >= 5 && (time() - $rec['t']) < 900;
}

function adm_throttle_record(bool $ok): void
{
    $all = storage_read('attempts') ?? [];
    $now = time();
    $all = array_filter($all, fn ($r) => ($now - ($r['t'] ?? 0)) < 900);
    $k = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '');
    if ($ok) {
        unset($all[$k]);
    } else {
        $all[$k] = ['n' => (($all[$k]['n'] ?? 0) + 1), 't' => $now];
    }
    storage_write('attempts', $all);
}

/* ---------- Vykreslenie ---------- */

function adm_render(string $view, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    ob_start();
    include __DIR__ . '/views/admin/' . $view . '.php';
    $content = (string) ob_get_clean();
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    include __DIR__ . '/views/admin/layout.php';
}

function adm_go(string $to): never
{
    header('Location: ' . url($to), true, 303);
    exit;
}

/* ---------- Načítanie a uloženie dát editora ---------- */

function adm_current(string $key, ?string $group, ?string $wrap): array
{
    $pages = adm_pages();
    if ($key === 'texty') {
        $v = [];
        foreach (array_keys(text_groups()[$group]['fields']) as $k) {
            $v[$k] = t($k);
        }
        return $v;
    }
    $store = $pages[$key][3];
    if ($store === 'images') {
        return storage_read('images') ?? [];
    }
    if ($store === 'config') {
        $c = $GLOBALS['CONFIG'];
        $c['therapist'] = ($c['therapist'] ?? []) + ['name' => '', 'role' => '', 'photo' => '', 'bio' => []];
        return $c;
    }
    $data = $GLOBALS[strtoupper(substr($store, 5))];
    return $wrap ? [$wrap => $data] : $data;
}

/** Overí a uloží. Vráti zoznam chýb (prázdny = uložené). */
function adm_save(string $key, ?string $group, ?string $wrap, array $p): array
{
    $pages = adm_pages();
    $store = $pages[$key][3] ?? null;
    $errs = [];

    if ($key === 'texty') {
        $over = storage_read('texts') ?? [];
        $def = text_defaults();
        foreach ($p as $k => $v) {
            if ($v === ($def[$k] ?? '')) {
                unset($over[$k]);
            } else {
                $over[$k] = $v;
            }
        }
        return storage_write('texts', $over) ? [] : ['Uloženie sa nepodarilo (skontrolujte práva k priečinku app/storage).'];
    }

    if ($store === 'images') {
        return storage_write('images', $p) ? [] : ['Uloženie sa nepodarilo.'];
    }

    if ($store === 'config') {
        if (!filter_var($p['email'], FILTER_VALIDATE_EMAIL)) {
            $errs[] = 'E-mail na webe nie je platný.';
        }
        if (!filter_var($p['mail_to'], FILTER_VALIDATE_EMAIL)) {
            $errs[] = 'E-mail pre objednávky nie je platný.';
        }
        foreach (['whatsapp' => 'WhatsApp', 'facebook' => 'Facebook', 'instagram' => 'Instagram'] as $k => $label) {
            if ($p[$k] !== '' && !preg_match('#^https://[^\s"<>]+$#i', $p[$k])) {
                $errs[] = "Odkaz „{$label}“ musí začínať https://";
            }
        }
        $digits = preg_replace('/\D/', '', $p['phone']);
        if (strlen($digits) < 9) {
            $errs[] = 'Telefón nie je platný.';
        }
        if ($errs) {
            return $errs;
        }
        $p['phone_href'] = '+' . (str_starts_with($digits, '0') ? '421' . substr($digits, 1) : $digits);
        $p['area'] = $p['area'] ?: ['Trnavský kraj', 'Bratislavský kraj'];
        return storage_write('config', $p) ? [] : ['Uloženie sa nepodarilo.'];
    }

    $data = $wrap ? ($p[$wrap] ?? []) : $p;

    if ($key === 'pribehy') {
        foreach ($data as $i => &$s) {
            $s['before'] = $s['before'] ?: $s['after'];
            $s['after'] = $s['after'] ?: $s['before'];
            if ($s['before'] === '') {
                $errs[] = 'Príbeh „' . $s['name'] . '“ potrebuje aspoň jednu fotku.';
            }
        }
        unset($s);
    }
    if ($key === 'sluzby' && count($data) < 1) {
        $errs[] = 'Musí zostať aspoň jedna služba.';
    }
    if ($errs) {
        return $errs;
    }
    return storage_write($store, $data) ? [] : ['Uloženie sa nepodarilo (skontrolujte práva k priečinku app/storage).'];
}

function adm_reset(string $key, ?string $group): void
{
    $pages = adm_pages();
    $store = $pages[$key][3] ?? null;
    if ($key === 'texty') {
        $over = storage_read('texts') ?? [];
        foreach (array_keys(text_groups()[$group]['fields']) as $k) {
            unset($over[$k]);
        }
        storage_write('texts', $over);
        return;
    }
    if ($store) {
        storage_reset($store);
    }
}

/* ---------- Smerovanie ---------- */

header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');
header('Referrer-Policy: same-origin');

adm_start();
$sub = trim(substr($path, strlen('/admin')), '/');
$isPost = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';

// Príliš veľký POST (napr. veľa fotiek naraz) PHP celý zahodí.
if ($isPost && empty($_POST) && empty($_FILES) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    adm_flash('err', 'Nahrávané údaje sú príliš veľké. Nahrajte menej alebo menších fotiek naraz.');
    adm_go('/admin' . ($sub !== '' ? '/' . $sub : ''));
}

if ($sub === 'login') {
    if (adm_authed()) {
        adm_go('/admin');
    }
    $hash = adm_admin_hash();
    if ($isPost) {
        if (!adm_csrf_ok()) {
            adm_flash('err', 'Platnosť formulára vypršala, skúste to znova.');
        } elseif ($hash === null) {
            adm_flash('err', 'Prihlásenie nie je nastavené.');
        } elseif (adm_throttle_blocked()) {
            adm_flash('err', 'Príliš veľa pokusov. Skúste to znova o 15 minút.');
        } elseif (is_string($_POST['password'] ?? null) && password_verify($_POST['password'], $hash)) {
            adm_throttle_record(true);
            session_regenerate_id(true);
            $_SESSION['ok'] = true;
            $_SESSION['csrf'] = bin2hex(random_bytes(24));
            adm_go('/admin');
        } else {
            adm_throttle_record(false);
            usleep(500000);
            adm_flash('err', 'Nesprávne heslo.');
        }
        adm_go('/admin/login');
    }
    adm_render('login', ['configured' => $hash !== null]);
    exit;
}

if (!adm_authed()) {
    adm_go('/admin/login');
}

if ($sub === 'logout' && $isPost && adm_csrf_ok()) {
    $_SESSION = [];
    session_destroy();
    header('Location: ' . url('/admin/login'), true, 303);
    exit;
}

if ($sub === '') {
    adm_render('dashboard', ['pages' => adm_pages()]);
    exit;
}

if ($sub === 'heslo') {
    if ($isPost) {
        $cur = (string) ($_POST['current'] ?? '');
        $new = (string) ($_POST['new'] ?? '');
        $new2 = (string) ($_POST['new2'] ?? '');
        $errs = [];
        if (!adm_csrf_ok()) {
            $errs[] = 'Platnosť formulára vypršala.';
        }
        if (!($h = adm_admin_hash()) || !password_verify($cur, $h)) {
            $errs[] = 'Súčasné heslo nie je správne.';
        }
        if (mb_strlen($new) < 10) {
            $errs[] = 'Nové heslo musí mať aspoň 10 znakov.';
        }
        if ($new !== $new2) {
            $errs[] = 'Nové heslá sa nezhodujú.';
        }
        if ($errs) {
            adm_flash('err', $errs);
        } else {
            storage_write('admin', ['hash' => password_hash($new, PASSWORD_DEFAULT), 'changed' => date('c')]);
            session_regenerate_id(true);
            adm_flash('ok', 'Heslo bolo zmenené.');
        }
        adm_go('/admin/heslo');
    }
    adm_render('password');
    exit;
}

if (preg_match('#^edit/([a-z]+)$#', $sub, $m) && isset(adm_pages()[$m[1]])) {
    $key = $m[1];
    $page = adm_pages()[$key];
    $wrap = $page[4] ?? null;
    $group = null;
    if ($key === 'texty') {
        $group = (string) ($_REQUEST['g'] ?? '');
        if (!isset(text_groups()[$group])) {
            $group = (string) array_key_first(text_groups());
        }
    }
    $self = '/admin/edit/' . $key . ($group ? '?g=' . $group : '');
    $fields = adm_schema($key, $group);
    $values = adm_current($key, $group, $wrap);

    if ($isPost) {
        if (!adm_csrf_ok()) {
            adm_flash('err', 'Platnosť formulára vypršala, skúste to znova.');
            adm_go($self);
        }
        if (($_POST['action'] ?? '') === 'reset') {
            adm_reset($key, $group);
            adm_flash('ok', 'Obnovené predvolené hodnoty.');
            adm_go($self);
        }
        $errs = [];
        $parsed = adm_parse($fields, is_array($_POST['d'] ?? null) ? $_POST['d'] : [], [], $errs);
        if (!$errs) {
            $errs = adm_save($key, $group, $wrap, $parsed);
        }
        if (!$errs) {
            adm_flash('ok', 'Zmeny boli uložené a sú hneď vidieť na webe.');
            adm_go($self);
        }
        adm_flash('err', $errs);
        $values = $parsed;
    }

    adm_render('edit', compact('key', 'page', 'group', 'fields', 'values', 'self'));
    exit;
}

adm_go('/admin');
