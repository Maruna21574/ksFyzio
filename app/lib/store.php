<?php
declare(strict_types=1);

/*
 * Úložisko obsahu v JSON súboroch (app/storage/*.json).
 * Pôvodné údaje v app/data/*.php slúžia ako predvolené hodnoty; súbor v úložisku ich prekrýva.
 */

function storage_dir(): string
{
    return dirname(__DIR__) . '/storage';
}

function storage_file(string $name): string
{
    return storage_dir() . '/' . preg_replace('/[^a-z0-9_-]/i', '', $name) . '.json';
}

function storage_read(string $name): ?array
{
    $f = storage_file($name);
    if (!is_file($f)) {
        return null;
    }
    $d = json_decode((string) file_get_contents($f), true);
    return is_array($d) ? $d : null;
}

/** Zapíše JSON atomicky; predchádzajúcu verziu uloží do app/storage/backup (posledných 20). */
function storage_write(string $name, array $data): bool
{
    $dir = storage_dir();
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        return false;
    }
    $f = storage_file($name);
    if (is_file($f)) {
        storage_backup($name);
    }
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }
    $tmp = $f . '.tmp' . bin2hex(random_bytes(4));
    if (file_put_contents($tmp, $json, LOCK_EX) === false) {
        return false;
    }
    if (!@rename($tmp, $f)) {
        @unlink($tmp);
        return false;
    }
    return true;
}

function storage_backup(string $name): void
{
    $f = storage_file($name);
    if (!is_file($f)) {
        return;
    }
    $bk = storage_dir() . '/backup';
    if (!is_dir($bk)) {
        @mkdir($bk, 0755, true);
    }
    $base = preg_replace('/[^a-z0-9_-]/i', '', $name);
    @copy($f, $bk . '/' . $base . '-' . date('Ymd-His') . '.json');
    $old = glob($bk . '/' . $base . '-*.json') ?: [];
    rsort($old);
    foreach (array_slice($old, 20) as $o) {
        @unlink($o);
    }
}

/** Zmaže prekrytie – web sa vráti k predvoleným údajom (po zálohe). */
function storage_reset(string $name): void
{
    storage_backup($name);
    @unlink(storage_file($name));
}

/** Dataset: úložisko (ak existuje), inak predvolené údaje z app/data. */
function load_data(string $name)
{
    $o = storage_read('data-' . $name);
    return $o ?? require dirname(__DIR__) . "/data/$name.php";
}

/* ---------- Texty ---------- */

/** Predvolené texty: [skupina => ['title' => ..., 'fields' => [kľúč => [popis, typ, predvolené]]]] */
function text_groups(): array
{
    static $g;
    return $g ??= require dirname(__DIR__) . '/data/texts.php';
}

function text_defaults(): array
{
    static $d;
    if ($d === null) {
        $d = [];
        foreach (text_groups() as $grp) {
            foreach ($grp['fields'] as $k => $f) {
                $d[$k] = $f[2];
            }
        }
    }
    return $d;
}

function t(string $key): string
{
    static $over;
    $over ??= storage_read('texts') ?? [];
    $def = text_defaults();
    if (isset($over[$key]) && is_string($over[$key])) {
        return $over[$key];
    }
    return $def[$key] ?? '';
}

/** Text s riadkmi -> pole neprázdnych riadkov. */
function t_lines(string $key): array
{
    return array_values(array_filter(array_map('trim', explode("\n", t($key))), fn ($l) => $l !== ''));
}

/**
 * Bezpečný text s jednoduchým značkovaním: **tučné**, [[zvýraznené]], [[g:zelené]], [[r:červené]].
 * Všetko ostatné sa escapuje.
 */
function rich(string $s): string
{
    $h = e($s);
    $h = preg_replace_callback('/\[\[(?:([gr]):)?(.+?)\]\]/u', function ($m) {
        $cls = 'hl' . ($m[1] === 'g' ? ' hl--green' : ($m[1] === 'r' ? ' hl--red' : ''));
        return '<span class="' . $cls . '">' . $m[2] . '</span>';
    }, $h);
    return preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $h);
}

function th(string $key): string
{
    return rich(t($key));
}

/* ---------- Fotky webu ---------- */

function site_img_defaults(): array
{
    return ['hero' => 'assets/img/hero-dog.webp', 'mascot' => 'assets/img/mascot.webp', 'about' => 'assets/img/praxe-odmena.webp'];
}

function site_img(string $slot): string
{
    static $m;
    $m ??= storage_read('images') ?? [];
    $p = (string) ($m[$slot] ?? '');
    if ($p !== '' && is_file(dirname(__DIR__, 2) . '/' . $p)) {
        return $p;
    }
    return site_img_defaults()[$slot] ?? '';
}

/** Menšia verzia (640 px) pre srcset – existuje len pre hlavnú fotku. */
function site_img_small(string $slot): string
{
    $p = site_img($slot);
    if ($p === site_img_defaults()[$slot]) {
        return $slot === 'hero' ? 'assets/img/hero-dog-640.webp' : $p;
    }
    $s = preg_replace('/(\.\w+)$/', '-640$1', $p);
    return is_file(dirname(__DIR__, 2) . '/' . $s) ? $s : $p;
}
