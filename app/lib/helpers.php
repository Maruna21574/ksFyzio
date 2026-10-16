<?php
declare(strict_types=1);

function cfg(string $key, $default = null)
{
    return $GLOBALS['CONFIG'][$key] ?? $default;
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Podadresár, v ktorom web beží ('' ak v koreni domény). */
function base_path(): string
{
    static $b;
    if ($b === null) {
        $b = rtrim(str_replace(chr(92), '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');
    }
    return $b;
}

function url(string $path = '/'): string
{
    return base_path() . '/' . ltrim($path, '/');
}

function absolute_url(string $path = '/'): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $host = preg_replace('/[^a-zA-Z0-9.\-:]/', '', $host);
    $scheme = ($https || !is_local_host()) ? 'https' : 'http';
    return $scheme . '://' . $host . url($path);
}

function is_local_host(): bool
{
    $h = strtolower($_SERVER['HTTP_HOST'] ?? '');
    return (bool) preg_match('/(^localhost|\.test$|\.local$|^127\.|^192\.168\.)/', explode(':', $h)[0]);
}

/** URL súboru z assets/ s cache-busting parametrom. */
function asset(string $path): string
{
    $file = dirname(__DIR__, 2) . '/' . ltrim($path, '/');
    $v = is_file($file) ? '?v=' . filemtime($file) : '';
    return url($path) . $v;
}

function icon(string $name, string $class = ''): string
{
    $c = 'icon' . ($class !== '' ? ' ' . $class : '');
    return '<svg class="' . e($c) . '" aria-hidden="true" focusable="false"><use href="#i-' . e($name) . '"/></svg>';
}

/** Nastaví meta údaje stránky (title, description, ...). */
function meta(array $m): void
{
    $GLOBALS['META'] = array_merge($GLOBALS['META'], $m);
}

function view(string $name, array $vars = []): string
{
    extract($vars, EXTR_SKIP);
    ob_start();
    include dirname(__DIR__) . '/views/' . $name . '.php';
    return (string) ob_get_clean();
}

function active(string $path, bool $prefix = true): string
{
    $cur = $GLOBALS['CURRENT_PATH'] ?? '/';
    $on = $prefix && $path !== '/' ? str_starts_with($cur, $path) : $cur === $path;
    return $on ? ' aria-current="page"' : '';
}

function find_by_slug(array $items, string $slug): ?array
{
    foreach ($items as $it) {
        if ($it['slug'] === $slug) {
            return $it;
        }
    }
    return null;
}

function respond_status(int $code): void
{
    http_response_code($code);
}

function redirect(string $to, int $code = 301): never
{
    header('Location: ' . $to, true, $code);
    exit;
}

/** Router: vráti [view, vars, status] */
function route(string $path): array
{
    $services = $GLOBALS['SERVICES'];
    $articles = $GLOBALS['ARTICLES'];

    $static = [
        '/'                      => 'home',
        '/sluzby'                => 'services',
        '/o-nas'                 => 'about',
        '/ako-to-funguje'        => 'process',
        '/tipy'                  => 'tips',
        '/rezervacia'            => 'booking',
        '/test-pohybu'           => 'quiz',
        '/vek-psa'               => 'dogage',
        '/cennik'                => 'pricing',
        '/pribehy'               => 'stories',
        '/kontakt'               => 'contact',
        '/ochrana-osobnych-udajov' => 'privacy',
    ];
    if (isset($static[$path])) {
        return [$static[$path], [], 200];
    }
    if (preg_match('#^/sluzby/([a-z0-9-]+)$#', $path, $m) && ($s = find_by_slug($services, $m[1]))) {
        return ['service', ['service' => $s], 200];
    }
    if (preg_match('#^/tipy/([a-z0-9-]+)$#', $path, $m) && ($a = find_by_slug($articles, $m[1]))) {
        return ['article', ['article' => $a], 200];
    }
    return ['404', [], 404];
}

function sitemap_urls(): array
{
    $urls = ['/', '/sluzby', '/ako-to-funguje', '/o-nas', '/tipy', '/test-pohybu', '/vek-psa', '/rezervacia', '/kontakt', '/ochrana-osobnych-udajov'];
    if (!cfg('demo_pricing')) {
        $urls[] = '/cennik';
    }
    if (!cfg('demo_content')) {
        $urls[] = '/pribehy';
    }
    foreach ($GLOBALS['SERVICES'] as $s) {
        $urls[] = '/sluzby/' . $s['slug'];
    }
    foreach ($GLOBALS['ARTICLES'] as $a) {
        $urls[] = '/tipy/' . $a['slug'];
    }
    return $urls;
}

function format_date_sk(string $iso): string
{
    $months = ['', 'januára', 'februára', 'marca', 'apríla', 'mája', 'júna', 'júla', 'augusta', 'septembra', 'októbra', 'novembra', 'decembra'];
    $t = strtotime($iso);
    return $t ? (int) date('j', $t) . '. ' . $months[(int) date('n', $t)] . ' ' . date('Y', $t) : $iso;
}
