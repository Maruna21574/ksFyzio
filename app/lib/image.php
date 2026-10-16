<?php
declare(strict_types=1);

/**
 * Spracovanie nahratých fotiek: kontrola typu, prekódovanie cez GD (odstráni skryté dáta),
 * zmenšenie a uloženie do assets/uploads pod náhodným názvom.
 * Vráti ['path' => 'assets/uploads/...'] alebo ['error' => 'text'].
 */
function image_save_upload(array $file, string $prefix, int $maxW = 1600, bool $small = false): array
{
    $err = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
        return ['error' => 'Fotka je príliš veľká (limit servera).'];
    }
    if ($err !== UPLOAD_ERR_OK) {
        return ['error' => 'Fotku sa nepodarilo nahrať.'];
    }
    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        return ['error' => 'Neplatný súbor.'];
    }
    if (($file['size'] ?? 0) > 12 * 1024 * 1024) {
        return ['error' => 'Fotka môže mať najviac 12 MB.'];
    }
    $info = @getimagesize($tmp);
    if (!$info || !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
        return ['error' => 'Povolené sú len fotky vo formáte JPG, PNG alebo WebP.'];
    }
    [$w, $h] = $info;
    if ($w < 1 || $h < 1 || $w > 10000 || $h > 10000 || $w * $h > 50_000_000) {
        return ['error' => 'Rozmery fotky sú príliš veľké.'];
    }
    if (!function_exists('imagecreatefromstring')) {
        return ['error' => 'Na serveri chýba podpora spracovania obrázkov (GD).'];
    }
    @ini_set('memory_limit', '512M');
    $img = @imagecreatefromstring((string) file_get_contents($tmp));
    if (!$img) {
        return ['error' => 'Fotku sa nepodarilo prečítať.'];
    }

    if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $ex = @exif_read_data($tmp);
        $rot = [3 => 180, 6 => -90, 8 => 90][$ex['Orientation'] ?? 0] ?? 0;
        if ($rot !== 0 && ($r = imagerotate($img, $rot, 0))) {
            $img = $r;
        }
    }

    $dir = dirname(__DIR__, 2) . '/assets/uploads';
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        return ['error' => 'Nepodarilo sa vytvoriť priečinok pre fotky.'];
    }
    $ht = $dir . '/.htaccess';
    if (!is_file($ht)) {
        @file_put_contents($ht, "# Len obrázky\n<FilesMatch \"\\.(php|phtml|phar|pl|py|cgi)$\">\n  Require all denied\n</FilesMatch>\nOptions -ExecCGI -Indexes\n");
    }

    $webp = function_exists('imagewebp');
    $ext = $webp ? 'webp' : 'jpg';
    $base = preg_replace('/[^a-z0-9-]/', '', strtolower($prefix)) . '-' . date('Ymd') . '-' . bin2hex(random_bytes(3));

    $write = function ($src, int $width) use ($dir, $base, $ext, $webp, $small) {
        $sw = imagesx($src);
        $sh = imagesy($src);
        $nw = min($sw, $width);
        $nh = (int) round($sh * ($nw / $sw));
        $dst = imagecreatetruecolor($nw, $nh);
        if ($webp) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        } else {
            imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $sw, $sh);
        $name = $base . ($width === 640 && $small ? '-640' : '') . '.' . $ext;
        $ok = $webp ? imagewebp($dst, $dir . '/' . $name, 82) : imagejpeg($dst, $dir . '/' . $name, 86);
        return $ok ? $name : null;
    };

    $main = $write($img, $maxW);
    if ($main === null) {
        return ['error' => 'Fotku sa nepodarilo uložiť.'];
    }
    if ($small) {
        $write($img, 640);
    }
    return ['path' => 'assets/uploads/' . $main];
}

/** Bezpečná relatívna cesta k obrázku (assets/img alebo assets/uploads). */
function image_valid_path(string $p): bool
{
    return (bool) preg_match('#^assets/(img|uploads)/[A-Za-z0-9._-]+$#', $p) && !str_contains($p, '..');
}
