<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

// --- Cesta požiadavky (bez podadresára a query stringu)
$uri  = rawurldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
$base = base_path();
if ($base !== '' && str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}
$path = '/' . trim($uri, '/');
$path = preg_replace('#/+#', '/', $path);

// /index.php a koncové lomítko -> kanonický tvar
if ($path === '/index.php') {
    redirect(url('/'));
}
if ($uri !== '/' && $uri !== '' && str_ends_with($uri, '/') && $path !== '/') {
    redirect(url($path));
}

// --- Strojové súbory
if ($path === '/robots.txt') {
    header('Content-Type: text/plain; charset=UTF-8');
    echo "User-agent: *\nAllow: /\nDisallow: /admin\n\nSitemap: " . absolute_url('/sitemap.xml') . "\n";
    exit;
}
if ($path === '/sitemap.xml') {
    header('Content-Type: application/xml; charset=UTF-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach (sitemap_urls() as $u) {
        echo '  <url><loc>' . e(absolute_url($u)) . '</loc></url>' . "\n";
    }
    echo "</urlset>\n";
    exit;
}

// --- Administrácia
if ($path === "/admin" || str_starts_with($path, "/admin/")) {
    require __DIR__ . "/app/admin.php";
    exit;
}

// --- Stránky
[$page, $vars, $status] = route($path);
$GLOBALS['CURRENT_PATH'] = $path;

$formPages = ['booking', 'contact'];
if (in_array($page, $formPages, true)) {
    $vars = array_merge($vars, handle_form($page));
}

http_response_code($status);
if ($status === 404) {
    header('X-Robots-Tag: noindex');
}

$content = view('pages/' . $page, $vars);
echo view('layout', ['content' => $content, 'page' => $page]);
