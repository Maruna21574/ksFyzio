<?php
/** @var string $content */
/** @var string $page */
$m = $GLOBALS['META'];
$siteName = cfg('name');
$title = isset($m['title'])
    ? $m['title'] . ' | ' . $siteName
    : $siteName . ' – Veterinárna fyzioterapia pre psov | Sereď, Trnavský a Bratislavský kraj';
$desc = $m['description'] ?? 'Veterinárna fyzioterapia pre psov. Rehabilitácie po úrazoch a operáciách, uvoľnenie svalov, podpora pohybu starších psov a výjazd domov v Trnavskom a Bratislavskom kraji.';
$canonical = absolute_url($GLOBALS['CURRENT_PATH'] ?? '/');
$ogImage = absolute_url($m['image'] ?? 'assets/img/og-image.jpg');
$noindex = $page === '404' || !empty($m['noindex']);

$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $siteName,
    'description' => 'Veterinárna fyzioterapia pre psov – rehabilitácie, uvoľnenie svalov, podpora pohybu starších psov, výjazdy domov.',
    'url' => absolute_url('/'),
    'image' => absolute_url('assets/img/og-image.jpg'),
    'logo' => absolute_url('assets/img/logo.png'),
    'telephone' => cfg('phone_href'),
    'email' => cfg('email'),
    'slogan' => cfg('tagline'),
    'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => cfg('city'),
        'postalCode' => cfg('zip'),
        'addressRegion' => cfg('region'),
        'addressCountry' => 'SK',
    ],
    'areaServed' => array_map(fn ($a) => ['@type' => 'AdministrativeArea', 'name' => $a], ['Trnavský kraj', 'Bratislavský kraj']),
];
$sameAs = array_values(array_filter([cfg('facebook'), cfg('instagram')]));
if ($sameAs) {
    $schema['sameAs'] = $sameAs;
}
?>
<!doctype html>
<html lang="sk">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?></title>
  <meta name="description" content="<?= e($desc) ?>">
  <meta name="theme-color" content="#0b6fa8">
  <link rel="canonical" href="<?= e($canonical) ?>">
<?php if ($noindex): ?>  <meta name="robots" content="noindex">
<?php endif; ?>

  <meta property="og:type" content="website">
  <meta property="og:locale" content="<?= e(cfg('locale')) ?>">
  <meta property="og:site_name" content="<?= e($siteName) ?>">
  <meta property="og:title" content="<?= e($title) ?>">
  <meta property="og:description" content="<?= e($desc) ?>">
  <meta property="og:url" content="<?= e($canonical) ?>">
  <meta property="og:image" content="<?= e($ogImage) ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta name="twitter:card" content="summary_large_image">

  <link rel="icon" href="<?= e(url('favicon.ico')) ?>" sizes="any">
  <link rel="icon" type="image/png" sizes="96x96" href="<?= e(asset('assets/img/favicon-96.png')) ?>">
  <link rel="apple-touch-icon" href="<?= e(asset('assets/img/apple-touch-icon.png')) ?>">
  <link rel="manifest" href="<?= e(url('site.webmanifest')) ?>">

  <link rel="preload" href="<?= e(url('assets/fonts/nunito-latin-wght-normal.woff2')) ?>" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="<?= e(asset('assets/css/main.css')) ?>">
  <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?></script>
</head>
<body class="page-<?= e($page) ?>">
  <a class="skip-link" href="#obsah">Preskočiť na obsah</a>
  <?php include __DIR__ . '/partials/icons.php'; ?>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main id="obsah">
<?= $content ?>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>

  <div class="callbar" role="navigation" aria-label="Rýchly kontakt">
    <a class="callbar__btn callbar__btn--call" href="tel:<?= e(cfg('phone_href')) ?>"><?= icon('phone') ?> Zavolať</a>
    <a class="callbar__btn callbar__btn--book" href="<?= e(url('/rezervacia')) ?>"><?= icon('calendar') ?> Objednať sa</a>
  </div>

  <button class="to-top" type="button" aria-label="Späť na začiatok stránky" data-to-top hidden>
    <svg class="icon" aria-hidden="true" focusable="false"><use href="#i-arrow"/></svg>
  </button>

  <script src="<?= e(asset('assets/js/main.js')) ?>" defer></script>
</body>
</html>
