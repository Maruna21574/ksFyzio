<!doctype html>
<html lang="sk">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Administrácia | <?= e(cfg('name')) ?></title>
  <link rel="icon" href="<?= e(url('favicon.ico')) ?>">
  <link rel="stylesheet" href="<?= e(asset('assets/css/admin.css')) ?>">
  <script src="<?= e(asset('assets/js/admin.js')) ?>" defer></script>
</head>
<body>
<header class="top">
  <div class="wrap top__in">
    <a class="top__brand" href="<?= e(url('/admin')) ?>">⚙️ Administrácia <span><?= e(cfg('name')) ?></span></a>
    <?php if (adm_authed()): ?>
    <nav class="top__nav">
      <a href="<?= e(url('/')) ?>" target="_blank" rel="noopener">Zobraziť web ↗</a>
      <a href="<?= e(url('/admin/heslo')) ?>">Heslo</a>
      <form method="post" action="<?= e(url('/admin/logout')) ?>"><input type="hidden" name="_csrf" value="<?= e(adm_csrf()) ?>"><button class="linkbtn">Odhlásiť</button></form>
    </nav>
    <?php endif; ?>
  </div>
</header>
<main class="wrap">
  <?php if ($flash): ?>
    <div class="flash flash--<?= $flash[0] === 'ok' ? 'ok' : 'err' ?>" role="<?= $flash[0] === 'ok' ? 'status' : 'alert' ?>">
      <?php if (count($flash[1]) === 1): ?><?= e($flash[1][0]) ?>
      <?php else: ?><strong>Opravte prosím:</strong><ul><?php foreach ($flash[1] as $m): ?><li><?= e($m) ?></li><?php endforeach; ?></ul><?php endif; ?>
    </div>
  <?php endif; ?>
  <?= $content ?>
</main>
</body>
</html>
