<?php
declare(strict_types=1);

const FORM_MIN_SECONDS = 3;     // rýchlejšie odoslanie = bot
const FORM_MAX_PER_HOUR = 5;    // limit odoslaní na reláciu
const FORM_IP_PER_HOUR = 3;     // limit úspešných odoslaní z jednej IP adresy
const FORM_IP_PER_DAY = 10;
const FORM_CONFIRM_PER_DAY = 1; // potvrdení klientovi na jednu e-mailovú adresu za deň
const FORM_MAX_LINKS = 1;       // najviac odkazov v správe

/* ---------- Limit podľa IP / kľúča (app/storage/ratelimit.json) ---------- */

function client_ip_key(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $bin = @inet_pton($ip);
    if ($bin !== false && strlen($bin) === 16) { // IPv6: počítame celú sieť /64
        $ip = bin2hex(substr($bin, 0, 8));
    }
    return hash('sha256', $ip);
}

/** Počet záznamov kľúča v danom okne (sekundy). */
function rate_count(string $key, int $window): int
{
    $all = json_decode((string) @file_get_contents(storage_dir() . '/ratelimit.json'), true);
    $now = time();
    return count(array_filter((array) ($all[$key] ?? []), fn ($t) => $t > $now - $window));
}

/** Zapíše udalosť pre kľúč; záznamy staršie ako 24 h sa zahadzujú. */
function rate_hit(string $key): void
{
    $dir = storage_dir();
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        return;
    }
    $fp = @fopen($dir . '/ratelimit.json', 'c+');
    if (!$fp) {
        return;
    }
    if (flock($fp, LOCK_EX)) {
        $all = json_decode((string) stream_get_contents($fp), true);
        $all = is_array($all) ? $all : [];
        $now = time();
        foreach ($all as $k => $times) {
            $all[$k] = array_values(array_filter((array) $times, fn ($t) => $t > $now - 86400));
            if (!$all[$k]) {
                unset($all[$k]);
            }
        }
        $all[$key][] = $now;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($all));
        fflush($fp);
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

function ip_rate_blocked(): bool
{
    $k = 'ip:' . client_ip_key();
    return rate_count($k, 3600) >= FORM_IP_PER_HOUR || rate_count($k, 86400) >= FORM_IP_PER_DAY;
}

/* ---------- Proof-of-work (vlastná ochrana bez služieb tretích strán) ----------
 * Server dá prehliadaču náhodnú „soľ“; prehliadač na pozadí nájde číslo (nonce), pre ktoré
 * SHA-256("soľ:nonce") začína aspoň POW_BITS nulovými bitmi. Človeka to nestojí nič (pár sekúnd na pozadí),
 * hromadné odosielanie robotom sa však výrazne predraží. Vyžaduje JavaScript a bezpečný kontext (HTTPS). */

const POW_BITS = 15;

/** Lokálny vývoj býva na http://*.test (bez crypto.subtle), tam kontrolu preskakujeme. */
function pow_enabled(): bool
{
    return !is_local_host();
}

function pow_salt(): string
{
    return $_SESSION['pow'] ??= bin2hex(random_bytes(12));
}

function pow_rotate(): void
{
    $_SESSION['pow'] = bin2hex(random_bytes(12));
}

/** Overí nonce voči soli v relácii; soľ sa po každom overení vymení (jednorazové). */
function pow_verify(string $nonce): bool
{
    $salt = (string) ($_SESSION['pow'] ?? '');
    pow_rotate();
    if ($salt === '' || !preg_match('/^[0-9]{1,12}$/', $nonce)) {
        return false;
    }
    $hash = hash('sha256', $salt . ':' . $nonce, true);
    $bits = 0;
    foreach (str_split($hash) as $byte) {
        $b = ord($byte);
        if ($b === 0) {
            $bits += 8;
            continue;
        }
        $bits += 7 - (int) floor(log($b, 2));
        break;
    }
    return $bits >= POW_BITS;
}

/** Skryté polia pre formulár: soľ pre skript a miesto pre výsledok. */
function pow_fields(): string
{
    if (!pow_enabled()) {
        return '';
    }
    return '<input type="hidden" name="pow" value="" data-pow-salt="' . e(pow_salt()) . '" data-pow-bits="' . POW_BITS . '">';
}

/** Počet odkazov v texte (http://, https://, www.). */
function count_links(string $s): int
{
    return (int) preg_match_all('~(?:https?://|www\.)\S+~iu', $s);
}

function form_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !is_local_host(), 'path' => '/']);
        session_name('ksf');
        session_start();
    }
}

function time_slots(): array
{
    return ['Dopoludnia', 'Popoludní', 'Podvečer', 'Kedykoľvek'];
}

function form_service_options(): array
{
    $o = [];
    foreach ($GLOBALS['SERVICES'] as $s) {
        $o[$s['slug']] = $s['title'];
    }
    $o['neviem'] = 'Neviem – poraďte mi';
    return $o;
}

function clean_line(?string $v, int $max = 200): string
{
    $v = trim((string) $v);
    $v = preg_replace('/[\r\n\t]+/', ' ', $v);
    return mb_substr($v, 0, $max);
}

function clean_text(?string $v, int $max = 3000): string
{
    $v = trim(str_replace("\r\n", "\n", (string) $v));
    $v = preg_replace('/[^\P{C}\n]+/u', '', $v);
    return mb_substr($v, 0, $max);
}

/** Spracuje GET/POST pre formulár. Vráti premenné pre view. */
function handle_form(string $which): array
{
    form_session();

    $state = ['values' => [], 'errors' => [], 'sent' => false, 'error_global' => '', 'csrf' => ''];

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        $_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(24));
        $_SESSION['form_t'] = time();
        if ($which === 'booking' && isset($_GET['sluzba'])) {
            $slug = (string) $_GET['sluzba'];
            if (isset(form_service_options()[$slug])) {
                $state['values']['service'] = $slug;
            }
        }
        if ($which === 'booking' && isset($_GET['signaly'])) {
            $picked = [];
            foreach (explode(',', (string) $_GET['signaly']) as $i) {
                $i = (int) $i;
                if (isset($GLOBALS['SIGNALS'][$i])) {
                    $picked[$i] = '- ' . $GLOBALS['SIGNALS'][$i];
                }
            }
            if ($picked) {
                $state['values']['message'] = "Všimol/a som si týchto zmien:\n" . implode("\n", $picked);
            }
        }
        $state['csrf'] = $_SESSION['csrf'];
        return ['form' => $state];
    }

    $wantsJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')
        || ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';

    $finish = function (array $state) use ($wantsJson): array {
        if ($wantsJson) {
            header('Content-Type: application/json; charset=UTF-8');
            header('Cache-Control: no-store');
            echo json_encode([
                'ok'     => $state['sent'],
                'errors' => $state['errors'],
                'error'  => $state['error_global'],
                'csrf'   => $_SESSION['csrf'] ?? '',
                'pow'    => pow_enabled() ? pow_salt() : '',
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        return ['form' => $state];
    };

    $in = $_POST;
    $state['csrf'] = $_SESSION['csrf'] ?? '';

    // Ochrana: CSRF, honeypot, čas, limit
    $okToken = isset($_SESSION['csrf'], $in['csrf']) && hash_equals($_SESSION['csrf'], (string) $in['csrf']);
    if (!$okToken) {
        $_SESSION['csrf'] = bin2hex(random_bytes(24));
        $_SESSION['form_t'] = time() - FORM_MIN_SECONDS; // token sme práve obnovili, nech sa dá hneď odoslať znova
        $state['csrf'] = $_SESSION['csrf'];
        $state['error_global'] = 'Platnosť formulára vypršala. Skúste ho prosím odoslať znova.';
        $state['values'] = $in;
        return $finish($state);
    }
    if (!empty($in['website'])) { // honeypot – tvárime sa, že sa podarilo
        $state['sent'] = true;
        return $finish($state);
    }
    if (time() - (int) ($_SESSION['form_t'] ?? 0) < FORM_MIN_SECONDS) {
        $state['error_global'] = 'Formulár bol odoslaný príliš rýchlo. Skúste to prosím ešte raz.';
        $state['values'] = $in;
        return $finish($state);
    }
    $_SESSION['sent_log'] = array_values(array_filter($_SESSION['sent_log'] ?? [], fn ($t) => $t > time() - 3600));
    if (count($_SESSION['sent_log']) >= FORM_MAX_PER_HOUR) {
        $state['error_global'] = 'Odoslali ste viac správ za krátky čas. Zavolajte nám prosím na ' . cfg('phone') . '.';
        return $finish($state);
    }

    if (ip_rate_blocked()) {
        $state['error_global'] = 'Z vašej adresy bolo odoslaných príliš veľa správ. Zavolajte nám prosím na ' . cfg('phone') . '.';
        return $finish($state);
    }

    $fn = $which === 'booking' ? 'validate_booking' : 'validate_contact';
    [$values, $errors] = $fn($in);
    $state['values'] = $values;
    $state['errors'] = $errors;

    if ($errors) {
        return $finish($state);
    }

    // Proof-of-work až po validácii polí, aby chyba vo formulári nespotrebovala jednorazovú soľ.
    if (pow_enabled() && !pow_verify((string) ($in['pow'] ?? ''))) {
        $state['error_global'] = 'Nepodarilo sa overiť, že formulár odosiela človek (vyžaduje sa zapnutý JavaScript). Skúste to prosím znova alebo nám zavolajte na ' . cfg('phone') . '.';
        $state['values'] = $values;
        return $finish($state);
    }

    if (send_form_mails($which, $values)) {
        rate_hit('ip:' . client_ip_key());
        $state['sent'] = true;
        $state['values'] = [];
        $_SESSION['sent_log'][] = time();
        $_SESSION['csrf'] = bin2hex(random_bytes(24));
        $_SESSION['form_t'] = time();
        $state['csrf'] = $_SESSION['csrf'];
    } else {
        $state['error_global'] = 'Správu sa nepodarilo odoslať. Skúste to prosím znova alebo nám zavolajte na ' . cfg('phone') . '.';
    }
    return $finish($state);
}

function validate_common(array $in, array &$v, array &$err): void
{
    $v['name'] = clean_line($in['name'] ?? '', 100);
    $v['email'] = clean_line($in['email'] ?? '', 150);
    $v['phone'] = clean_line($in['phone'] ?? '', 30);

    if (mb_strlen($v['name']) < 2) {
        $err['name'] = 'Zadajte prosím meno a priezvisko.';
    }
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
        $err['email'] = 'Zadajte prosím platný e-mail.';
    }
    if ($v['phone'] !== '' && !preg_match('/^\+?[0-9 ()\/-]{7,20}$/', $v['phone'])) {
        $err['phone'] = 'Telefónne číslo má nesprávny formát.';
    }
    $v['consent'] = !empty($in['consent']) ? '1' : '';
    if ($v['consent'] === '') {
        $err['consent'] = 'Pre odoslanie je potrebný súhlas so spracovaním údajov.';
    }
}

/** Krátke polia nesmú obsahovať odkazy; v správe je povolený najviac jeden. */
function validate_links(array $v, array &$err, array $short, string $messageField = 'message'): void
{
    foreach ($short as $f) {
        if (!isset($err[$f]) && count_links($v[$f] ?? '') > 0) {
            $err[$f] = 'Odkazy tu nie sú povolené.';
        }
    }
    if (!isset($err[$messageField]) && count_links($v[$messageField] ?? '') > FORM_MAX_LINKS) {
        $err[$messageField] = 'Správa môže obsahovať najviac jeden odkaz.';
    }
}

function validate_booking(array $in): array
{
    $v = [];
    $err = [];
    validate_common($in, $v, $err);

    if ($v['phone'] === '') {
        $err['phone'] = 'Zadajte prosím telefónne číslo, ozveme sa vám.';
    }
    $v['location'] = clean_line($in['location'] ?? '', 100);
    $v['dog_name'] = clean_line($in['dog_name'] ?? '', 60);
    $v['breed'] = clean_line($in['breed'] ?? '', 80);
    $v['age'] = clean_line($in['age'] ?? '', 40);
    $v['service'] = (string) ($in['service'] ?? '');
    $v['date'] = clean_line($in['date'] ?? '', 10);
    $v['time'] = (string) ($in['time'] ?? '');
    $v['message'] = clean_text($in['message'] ?? '', 2000);

    if ($v['location'] === '') {
        $err['location'] = 'Uveďte obec alebo mesto, kde sa pes nachádza.';
    }
    if ($v['dog_name'] === '') {
        $err['dog_name'] = 'Napíšte prosím meno psa.';
    }
    if (!isset(form_service_options()[$v['service']])) {
        $v['service'] = 'neviem';
    }
    if (!in_array($v['time'], time_slots(), true)) {
        $v['time'] = 'Kedykoľvek';
    }
    if ($v['date'] !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $v['date']);
        if (!$d || $d->format('Y-m-d') !== $v['date'] || $d < new DateTime('today')) {
            $err['date'] = 'Vyberte prosím dnešný alebo budúci dátum.';
        }
    }
    validate_links($v, $err, ['name', 'location', 'dog_name', 'breed', 'age']);
    return [$v, $err];
}

function validate_contact(array $in): array
{
    $v = [];
    $err = [];
    validate_common($in, $v, $err);
    $v['message'] = clean_text($in['message'] ?? '', 3000);
    if (mb_strlen($v['message']) < 5) {
        $err['message'] = 'Napíšte nám prosím, s čím vám môžeme pomôcť.';
    }
    validate_links($v, $err, ['name']);
    return [$v, $err];
}

function mail_footer(): string
{
    return "\n\n" . str_repeat('-', 30) . "\nOdoslané: " . date('j.n.Y H:i') . "\nSúhlas so spracovaním údajov: áno";
}

/** Zhrnutie údajov z formulára ako pole popisok => hodnota. */
function mail_summary(string $which, array $v): array
{
    if ($which === 'contact') {
        return [
            'Meno'    => $v['name'],
            'E-mail'  => $v['email'],
            'Telefón' => $v['phone'] ?: '–',
        ];
    }
    $svc = form_service_options()[$v['service']] ?? $v['service'];
    return [
        'Meno'       => $v['name'],
        'Telefón'    => $v['phone'],
        'E-mail'     => $v['email'],
        'Obec/mesto' => $v['location'],
        'Pes'        => $v['dog_name'],
        'Plemeno'    => $v['breed'] ?: '–',
        'Vek'        => $v['age'] ?: '–',
        'Služba'     => $svc,
        'Termín'     => ($v['date'] ? format_date_sk($v['date']) : 'neurčený') . ', ' . $v['time'],
    ];
}

/** Zhrnutie pre klienta – len hodnoty z pevných zoznamov, žiadny voľný text (ochrana pred zneužitím na spam). */
function client_summary(string $which, array $v): array
{
    return $which === 'booking' ? array_intersect_key(mail_summary('booking', $v), ['Služba' => 1, 'Termín' => 1]) : [];
}

function mail_text_rows(array $rows): string
{
    $out = [];
    foreach ($rows as $k => $val) {
        $out[] = $k . ':' . str_repeat(' ', max(1, 14 - mb_strlen($k . ':'))) . $val;
    }
    return implode("\n", $out);
}

function admin_mail_text(string $which, array $v): string
{
    $head = $which === 'booking' ? 'NOVÁ OBJEDNÁVKA Z WEBU' : 'NOVÁ SPRÁVA Z KONTAKTNÉHO FORMULÁRA';
    $label = $which === 'booking' ? 'Popis ťažkostí' : 'Správa';
    return $head . "\n" . str_repeat('-', 30) . "\n"
        . mail_text_rows(mail_summary($which, $v))
        . "\n\n" . $label . ":\n" . ($v['message'] ?: '–')
        . mail_footer();
}

function client_mail_text(string $which, array $v): string
{
    $booking = $which === 'booking';
    $intro = $booking
        ? 'ďakujeme za objednávku. Prijali sme ju a čoskoro sa vám ozveme, aby sme dohodli termín.'
        : 'ďakujeme za vašu správu. Prijali sme ju a čoskoro sa vám ozveme.';
    $rows = client_summary($which, $v);
    return 'Dobrý deň, ' . $v['name'] . ",\n\n" . $intro . "\n\n"
        . ($rows ? 'Zhrnutie objednávky' . "\n" . str_repeat('-', 30) . "\n" . mail_text_rows($rows) . "\n\n" : '')
        . 'Ak je to naliehavé, zavolajte nám na ' . cfg('phone') . ' alebo napíšte na ' . cfg('email') . ".\n\n"
        . "Tešíme sa na vás,\n" . cfg('name') . ' – ' . cfg('tagline') . "\n" . absolute_url('/') . "\n\n"
        . 'Tento e-mail ste dostali, lebo bol z vašej adresy odoslaný formulár na našom webe. Ak ste ho neodoslali vy, môžete ho ignorovať.';
}

function mail_html(string $title, string $preheader, string $inner): string
{
    return view('mail/layout', ['title' => $title, 'pre' => $preheader, 'inner' => $inner]);
}

/** Odošle upozornenie prevádzkovateľovi a potvrdenie klientovi. Úspech = upozornenie prevádzkovateľovi odišlo. */
function send_form_mails(string $which, array $v): bool
{
    $booking = $which === 'booking';
    $adminHtml = mail_html(
        $booking ? 'Nová objednávka z webu' : 'Nová správa z webu',
        $v['name'] . ($v['phone'] !== '' ? ' · ' . $v['phone'] : ''),
        view('mail/admin', ['which' => $which, 'v' => $v, 'rows' => mail_summary($which, $v)])
    );
    $ok = send_site_mail([
        'to'         => cfg('mail_to'),
        'subject'    => cfg('subject_prefix') . ' ' . ($booking ? 'Nová objednávka – ' : 'Nová správa – ') . $v['name'],
        'text'       => admin_mail_text($which, $v),
        'html'       => $adminHtml,
        'replyEmail' => $v['email'],
        'replyName'  => $v['name'],
        'fromName'   => cfg('name') . ' web',
    ]);
    if (!$ok) {
        return false;
    }

    // Potvrdenie klientovi – jeho zlyhanie nesmie zhodiť odoslanie formulára.
    // Na jednu adresu najviac raz denne, aby sa web nedal zneužiť na zasielanie pošty cudzím ľuďom.
    $mailKey = 'mail:' . hash('sha256', strtolower($v['email']));
    if (rate_count($mailKey, 86400) >= FORM_CONFIRM_PER_DAY) {
        return true;
    }
    rate_hit($mailKey);
    $clientHtml = mail_html(
        $booking ? 'Ďakujeme za objednávku' : 'Ďakujeme za správu',
        $booking ? 'Prijali sme vašu objednávku, čoskoro sa vám ozveme.' : 'Prijali sme vašu správu, čoskoro sa vám ozveme.',
        view('mail/client', ['which' => $which, 'v' => $v, 'rows' => client_summary($which, $v)])
    );
    send_site_mail([
        'to'         => $v['email'],
        'subject'    => ($booking ? 'Potvrdenie objednávky – ' : 'Prijali sme vašu správu – ') . cfg('name'),
        'text'       => client_mail_text($which, $v),
        'html'       => $clientHtml,
        'replyEmail' => cfg('mail_to'),
        'replyName'  => cfg('name'),
        'fromName'   => cfg('name'),
    ]);
    return true;
}

/** Odošle multipart (text + HTML) e-mail. Voľby: to, subject, text, html, replyEmail, replyName, fromName. */
function send_site_mail(array $o): bool
{
    $host = preg_replace('/^www\./', '', explode(':', $_SERVER['HTTP_HOST'] ?? 'localhost')[0]);
    $from = cfg('mail_from') ?: 'noreply@' . $host;

    $enc = fn (string $s): string => mb_encode_mimeheader($s, 'UTF-8', 'B', "\r\n");
    $b64 = fn (string $s): string => chunk_split(base64_encode($s), 76, "\r\n");
    $boundary = '=_ksf_' . bin2hex(random_bytes(12));

    $headers = [
        'From: ' . $enc($o['fromName']) . ' <' . $from . '>',
        'Reply-To: ' . $enc($o['replyName']) . ' <' . $o['replyEmail'] . '>',
        'MIME-Version: 1.0',
        'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
        'X-Mailer: ksfyzio-web',
    ];
    $body = '--' . $boundary . "\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n"
        . $b64($o['text'])
        . '--' . $boundary . "\r\n"
        . "Content-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n"
        . $b64($o['html'])
        . '--' . $boundary . "--\r\n";

    $ok = @mail($o['to'], $enc($o['subject']), $body, implode("\r\n", $headers), '-f' . $from);

    if (!$ok && is_local_host()) {
        // Lokálny vývoj (Laragon bez SMTP) – uložíme správu do súboru.
        $file = dirname(__DIR__) . '/storage/mail-dev.log';
        @file_put_contents($file, '=== ' . date('c') . ' -> ' . $o['to'] . "\nSubject: " . $o['subject'] . "\n" . $o['text'] . "\n\n", FILE_APPEND | LOCK_EX);
        return true;
    }
    return $ok;
}
