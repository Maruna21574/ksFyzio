<?php
declare(strict_types=1);

/* Vykresľovanie a spracovanie admin formulárov podľa schémy (pozri admin_schema.php). */

/* ===================== Pomocné ===================== */

function adm_slugify(string $s): string
{
    $s = mb_strtolower(trim($s));
    $s = strtr($s, [
        'á' => 'a', 'ä' => 'a', 'č' => 'c', 'ď' => 'd', 'é' => 'e', 'ě' => 'e', 'í' => 'i', 'ĺ' => 'l', 'ľ' => 'l',
        'ň' => 'n', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o', 'ŕ' => 'r', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ú' => 'u',
        'ü' => 'u', 'ý' => 'y', 'ž' => 'z',
    ]);
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    return trim((string) $s, '-') ?: 'polozka';
}

function adm_blocks_to_text(array $blocks): string
{
    $parts = [];
    foreach ($blocks as $b) {
        [$type, $c] = $b + [1 => ''];
        if ($type === 'h') {
            $parts[] = '## ' . $c;
        } elseif ($type === 'quote') {
            $parts[] = '> ' . $c;
        } elseif ($type === 'ul') {
            $parts[] = implode("\n", array_map(fn ($li) => '- ' . $li, (array) $c));
        } else {
            $parts[] = (string) $c;
        }
    }
    return implode("\n\n", $parts);
}

function adm_text_to_blocks(string $s): array
{
    $blocks = [];
    $p = [];
    $ul = [];
    $flush = function () use (&$blocks, &$p, &$ul) {
        if ($p) {
            $blocks[] = ['p', implode(' ', $p)];
            $p = [];
        }
        if ($ul) {
            $blocks[] = ['ul', $ul];
            $ul = [];
        }
    };
    foreach (explode("\n", str_replace("\r", '', trim($s))) as $line) {
        $line = trim($line);
        if ($line === '') {
            $flush();
        } elseif (str_starts_with($line, '## ')) {
            $flush();
            $blocks[] = ['h', trim(substr($line, 3))];
        } elseif (str_starts_with($line, '> ')) {
            $flush();
            $blocks[] = ['quote', trim(substr($line, 2))];
        } elseif (preg_match('/^[-*] (.+)$/u', $line, $m)) {
            if ($p) {
                $flush();
            }
            $ul[] = trim($m[1]);
        } else {
            if ($ul) {
                $flush();
            }
            $p[] = $line;
        }
    }
    $flush();
    return $blocks;
}

/** Súbor z $_FILES['up'] na zadanej ceste (rovnaká štruktúra ako pole v $_POST['d']). */
function adm_file(array $path): ?array
{
    if (!isset($_FILES['up']) || !is_array($_FILES['up']['name'] ?? null)) {
        return null;
    }
    $file = [];
    foreach (['name', 'type', 'tmp_name', 'error', 'size'] as $key) {
        $cur = $_FILES['up'][$key];
        foreach ($path as $p) {
            if (!is_array($cur) || !array_key_exists($p, $cur)) {
                return null;
            }
            $cur = $cur[$p];
        }
        if (is_array($cur)) {
            return null;
        }
        $file[$key] = $cur;
    }
    return $file;
}

/* ===================== Vykreslenie ===================== */

function adm_fields(array $fields, array $vals, string $base, int $depth, bool $tuple = false): string
{
    $h = '';
    foreach ($fields as $i => $f) {
        $v = $tuple ? ($vals[$i] ?? null) : ($vals[$f['k']] ?? null);
        $h .= adm_field($f, $v, $base . '[' . $f['k'] . ']', $depth);
    }
    return $h;
}

function adm_field(array $f, $v, string $name, int $depth): string
{
    $l = e($f['l']);
    $hint = !empty($f['hint']) ? '<small class="hint">' . e($f['hint']) . '</small>' : '';
    $req = !empty($f['req']) ? ' <b class="req">*</b>' : '';
    $max = isset($f['max']) ? ' maxlength="' . (int) $f['max'] . '"' : '';

    switch ($f['t']) {
        case 'text':
        case 'slug':
            return '<label class="fld"><span>' . $l . $req . '</span><input type="text" name="' . e($name) . '" value="' . e((string) $v) . '"' . $max
                . ($f['t'] === 'slug' ? ' pattern="[a-z0-9\-]*"' : '') . '>' . $hint . '</label>';

        case 'textarea':
            return '<label class="fld"><span>' . $l . $req . '</span><textarea name="' . e($name) . '" rows="' . (int) ($f['rows'] ?? 3) . '">' . e((string) $v) . '</textarea>' . $hint . '</label>';

        case 'lines':
            $txt = is_array($v) ? implode("\n", $v) : (string) $v;
            return '<label class="fld"><span>' . $l . '</span><textarea name="' . e($name) . '" rows="' . (int) ($f['rows'] ?? 4) . '">' . e($txt) . '</textarea>' . $hint . '</label>';

        case 'blocks':
            $txt = is_array($v) ? adm_blocks_to_text($v) : '';
            return '<label class="fld"><span>' . $l . '</span><textarea name="' . e($name) . '" rows="14">' . e($txt) . '</textarea>' . $hint . '</label>';

        case 'number':
            $val = is_numeric($v) ? (int) $v : (int) ($f['def'] ?? 0);
            return '<label class="fld fld--short"><span>' . $l . '</span><input type="number" name="' . e($name) . '" value="' . $val . '" min="' . (int) ($f['min'] ?? 0) . '" max="' . (int) ($f['max'] ?? 1000) . '"></label>';

        case 'checkbox':
            return '<label class="chk"><input type="checkbox" name="' . e($name) . '" value="1"' . ($v ? ' checked' : '') . '> <span>' . $l . '</span></label>';

        case 'select':
            $o = '';
            foreach ($f['options'] as $k => $text) {
                $o .= '<option value="' . e((string) $k) . '"' . ((string) $k === (string) $v ? ' selected' : '') . '>' . e($text) . '</option>';
            }
            return '<label class="fld fld--short"><span>' . $l . '</span><select name="' . e($name) . '">' . $o . '</select></label>';

        case 'image':
            $path = is_string($v) && image_valid_path($v) ? $v : '';
            $shown = $path !== '' ? $path : (string) ($f['default'] ?? '');
            $up = 'up' . substr($name, 1);
            return '<div class="fld img" data-img><span>' . $l . '</span><div class="img__row">'
                . '<img class="img__prev" data-img-prev alt=""' . ($shown !== '' ? ' src="' . e(asset($shown)) . '"' : ' hidden') . '>'
                . '<div class="img__ctl"><input type="hidden" name="' . e($name) . '" value="' . e($path) . '" data-img-val>'
                . '<input type="file" name="' . e($up) . '" accept="image/jpeg,image/png,image/webp" data-img-file>'
                . '<button type="button" class="btn btn--small" data-img-clear>' . (!empty($f['default']) ? 'Vrátiť pôvodnú' : 'Odstrániť fotku') . '</button>'
                . '<small class="hint">JPG, PNG alebo WebP, najviac 12 MB. Fotka sa automaticky zmenší.' . (!empty($f['hint']) ? ' ' . e($f['hint']) : '') . '</small></div></div></div>';

        case 'group':
            $vals = is_array($v) ? $v : [];
            return '<fieldset class="grp"><legend>' . $l . '</legend>' . adm_fields($f['fields'], $vals, $name, $depth + 1) . '</fieldset>';

        case 'repeater':
            return adm_repeater($f, is_array($v) ? array_values($v) : [], $name, $depth);
    }
    return '';
}

function adm_repeater(array $f, array $items, string $base, int $depth): string
{
    $tuple = !empty($f['tuple']);
    $marker = '__i' . $depth . '__';
    $h = '<div class="rep" data-rep data-depth="' . $depth . '"><div class="rep__head"><strong>' . e($f['l']) . '</strong></div><div class="rep__items" data-rep-items>';
    foreach ($items as $i => $item) {
        $h .= adm_rep_item($f, is_array($item) ? $item : [], $base . '[' . $i . ']', $depth, $tuple);
    }
    $h .= '</div><template data-rep-tpl>' . adm_rep_item($f, [], $base . '[' . $marker . ']', $depth, $tuple) . '</template>';
    $h .= '<button type="button" class="btn btn--add" data-rep-add>＋ ' . e($f['add'] ?? 'Pridať') . '</button></div>';
    return $h;
}

function adm_rep_item(array $f, array $item, string $base, int $depth, bool $tuple): string
{
    $first = $tuple ? ($item[0] ?? '') : ($item[$f['label'] ?? ($f['fields'][0]['k'] ?? '')] ?? '');
    $title = is_string($first) && trim($first) !== '' ? $first : 'Nová položka';
    return '<div class="rep__item" data-rep-item><div class="rep__bar"><strong class="rep__title" data-rep-title>' . e(mb_substr($title, 0, 70)) . '</strong>'
        . '<span class="rep__btns"><button type="button" class="btn btn--small" data-rep-up title="Posunúť vyššie">↑</button>'
        . '<button type="button" class="btn btn--small" data-rep-down title="Posunúť nižšie">↓</button>'
        . '<button type="button" class="btn btn--small btn--danger" data-rep-del data-confirm="Naozaj odstrániť túto položku?">Odstrániť</button></span></div>'
        . '<div class="rep__body">' . adm_fields($f['fields'], $item, $base, $depth + 1, $tuple) . '</div></div>';
}

/* ===================== Spracovanie ===================== */

function adm_clean(string $s, int $max, bool $multiline = false): string
{
    $s = str_replace("\r", '', $s);
    $s = preg_replace($multiline ? '/[\x00-\x08\x0B\x0C\x0E-\x1F]/u' : '/[\x00-\x1F]/u', $multiline ? '' : ' ', $s) ?? '';
    return mb_substr(trim($s), 0, $max);
}

function adm_parse(array $fields, array $post, array $path, array &$errs, bool $tuple = false): array
{
    $out = [];
    foreach ($fields as $f) {
        $out[$f['k']] = adm_parse_field($f, $post[$f['k']] ?? null, array_merge($path, [$f['k']]), $errs);
    }
    foreach ($fields as $f) {
        if ($f['t'] === 'slug') {
            $s = adm_slugify((string) $out[$f['k']] !== '' ? (string) $out[$f['k']] : (string) ($out[$f['src']] ?? ''));
            $out[$f['k']] = $s;
        }
    }
    return $tuple ? array_values($out) : $out;
}

function adm_parse_field(array $f, $raw, array $path, array &$errs)
{
    switch ($f['t']) {
        case 'text':
        case 'slug':
            $v = adm_clean(is_string($raw) ? $raw : '', (int) ($f['max'] ?? 300));
            if (!empty($f['req']) && $v === '') {
                $errs[] = '„' . $f['l'] . '“ je povinné.';
            }
            return $v;

        case 'textarea':
            $v = adm_clean(is_string($raw) ? $raw : '', (int) ($f['max'] ?? 4000), true);
            if (!empty($f['req']) && $v === '') {
                $errs[] = '„' . $f['l'] . '“ je povinné.';
            }
            return $v;

        case 'lines':
            $lines = array_map(fn ($l) => adm_clean($l, 400), explode("\n", str_replace("\r", '', is_string($raw) ? $raw : '')));
            return array_slice(array_values(array_filter($lines, fn ($l) => $l !== '')), 0, 100);

        case 'blocks':
            return adm_text_to_blocks(adm_clean(is_string($raw) ? $raw : '', 20000, true));

        case 'number':
            $n = filter_var($raw, FILTER_VALIDATE_INT);
            $n = $n === false ? (int) ($f['def'] ?? 0) : $n;
            return max((int) ($f['min'] ?? 0), min((int) ($f['max'] ?? 1000), $n));

        case 'checkbox':
            return !empty($raw);

        case 'select':
            return is_string($raw) && isset($f['options'][$raw]) ? $raw : (string) array_key_first($f['options']);

        case 'image':
            $cur = is_string($raw) && image_valid_path(trim($raw)) ? trim($raw) : '';
            $file = adm_file($path);
            if ($file && (int) $file['error'] !== UPLOAD_ERR_NO_FILE) {
                $r = image_save_upload($file, (string) ($f['prefix'] ?? 'foto'), (int) ($f['maxw'] ?? 1600), !empty($f['small']));
                if (isset($r['error'])) {
                    $errs[] = '„' . $f['l'] . '“: ' . $r['error'];
                    return $cur;
                }
                return $r['path'];
            }
            return $cur;

        case 'group':
            return adm_parse($f['fields'], is_array($raw) ? $raw : [], $path, $errs);

        case 'repeater':
            return adm_parse_repeater($f, is_array($raw) ? $raw : [], $path, $errs);
    }
    return null;
}

function adm_parse_repeater(array $f, array $raw, array $path, array &$errs): array
{
    $tuple = !empty($f['tuple']);
    $items = [];
    $n = 0;
    foreach ($raw as $idx => $itemRaw) {
        if (!is_array($itemRaw) || ++$n > 200) {
            continue;
        }
        $beforeErrs = count($errs);
        $item = adm_parse($f['fields'], $itemRaw, array_merge($path, [$idx]), $errs, false);
        $blank = true;
        foreach ($f['fields'] as $sf) {
            if (in_array($sf['t'], ['text', 'textarea', 'lines', 'image', 'blocks', 'repeater'], true) && !in_array($item[$sf['k']], ['', []], true)) {
                $blank = false;
            }
        }
        if ($blank) {
            array_splice($errs, $beforeErrs);
            continue;
        }
        $items[] = $item;
    }
    foreach ($f['fields'] as $sf) {
        if ($sf['t'] === 'slug') {
            $seen = [];
            foreach ($items as &$it) {
                $s = $base = $it[$sf['k']];
                for ($i = 2; isset($seen[$s]); $i++) {
                    $s = $base . '-' . $i;
                }
                $seen[$s] = true;
                $it[$sf['k']] = $s;
            }
            unset($it);
        }
    }
    if ($tuple) {
        $items = array_map(fn ($it) => array_values($it), $items);
    }
    return $items;
}
