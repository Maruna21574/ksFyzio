<?php
declare(strict_types=1);

const FORM_MIN_SECONDS = 3;     // rýchlejšie odoslanie = bot
const FORM_MAX_PER_HOUR = 5;    // limit odoslaní na reláciu

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

    $fn = $which === 'booking' ? 'validate_booking' : 'validate_contact';
    [$values, $errors] = $fn($in);
    $state['values'] = $values;
    $state['errors'] = $errors;

    if ($errors) {
        return $finish($state);
    }

    $subject = cfg('subject_prefix') . ' ' . ($which === 'booking' ? 'Nová objednávka – ' : 'Nová správa – ') . $values['name'];
    $body = $which === 'booking' ? booking_mail_body($values) : contact_mail_body($values);

    if (send_site_mail($subject, $body, $values['email'], $values['name'])) {
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
    return [$v, $err];
}

function booking_mail_body(array $v): string
{
    $svc = form_service_options()[$v['service']] ?? $v['service'];
    $lines = [
        'NOVÁ OBJEDNÁVKA Z WEBU',
        str_repeat('-', 30),
        'Meno:        ' . $v['name'],
        'Telefón:     ' . $v['phone'],
        'E-mail:      ' . $v['email'],
        'Obec/mesto:  ' . $v['location'],
        '',
        'Pes:         ' . $v['dog_name'],
        'Plemeno:     ' . ($v['breed'] ?: '–'),
        'Vek:         ' . ($v['age'] ?: '–'),
        '',
        'Služba:      ' . $svc,
        'Termín:      ' . ($v['date'] ? format_date_sk($v['date']) : 'neurčený') . ', ' . $v['time'],
        '',
        'Popis ťažkostí:',
        $v['message'] ?: '–',
    ];
    return implode("\n", $lines) . mail_footer();
}

function contact_mail_body(array $v): string
{
    $lines = [
        'NOVÁ SPRÁVA Z KONTAKTNÉHO FORMULÁRA',
        str_repeat('-', 30),
        'Meno:     ' . $v['name'],
        'E-mail:   ' . $v['email'],
        'Telefón:  ' . ($v['phone'] ?: '–'),
        '',
        $v['message'],
    ];
    return implode("\n", $lines) . mail_footer();
}

function mail_footer(): string
{
    return "\n\n" . str_repeat('-', 30) . "\nOdoslané: " . date('j.n.Y H:i') . "\nSúhlas so spracovaním údajov: áno";
}

function send_site_mail(string $subject, string $body, string $replyEmail, string $replyName): bool
{
    $host = preg_replace('/^www\./', '', explode(':', $_SERVER['HTTP_HOST'] ?? 'localhost')[0]);
    $from = cfg('mail_from') ?: 'noreply@' . $host;
    $to = cfg('mail_to');

    $enc = fn (string $s): string => mb_encode_mimeheader($s, 'UTF-8', 'B', "\r\n");
    $headers = [
        'From: ' . $enc(cfg('name') . ' web') . ' <' . $from . '>',
        'Reply-To: ' . $enc($replyName) . ' <' . $replyEmail . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        'X-Mailer: ksfyzio-web',
    ];

    $ok = @mail($to, $enc($subject), $body, implode("\r\n", $headers), '-f' . $from);

    if (!$ok && is_local_host()) {
        // Lokálny vývoj (Laragon bez SMTP) – uložíme správu do súboru.
        $file = dirname(__DIR__) . '/storage/mail-dev.log';
        @file_put_contents($file, '=== ' . date('c') . " -> $to\nSubject: $subject\n$body\n\n", FILE_APPEND | LOCK_EX);
        return true;
    }
    return $ok;
}
