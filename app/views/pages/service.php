<?php
/** @var array $service */
meta([
    'title' => $service['title'],
    'description' => $service['lead'],
]);
$headTitle = $service['title'];
$headLead = $service['lead'];
$crumbs = [['/sluzby', 'Služby']];
include dirname(__DIR__) . '/partials/page-head.php';

$others = array_filter($GLOBALS['SERVICES'], fn ($s) => $s['slug'] !== $service['slug']);
?>
<section class="section section--flush">
  <div class="container article-layout">
    <div class="prose">
      <h2><?= e(t('service_when_h2')) ?></h2>
      <ul class="checks">
        <?php foreach ($service['when'] as $w): ?><li><?= icon('check') ?> <?= e($w) ?></li><?php endforeach; ?>
      </ul>

      <h2><?= e(t('service_how_h2')) ?></h2>
      <ol class="steps steps--list">
        <?php foreach ($service['how'] as $i => [$t, $d]): ?>
          <li class="step">
            <span class="step__num"><?= $i + 1 ?></span>
            <h3><?= e($t) ?></h3>
            <p><?= e($d) ?></p>
          </li>
        <?php endforeach; ?>
      </ol>

      <p class="notice"><?= icon('shield') ?> <span><?= e($service['note']) ?></span></p>
    </div>

    <aside class="aside">
      <div class="aside__card aside__card--cta">
        <h2>Objednajte sa</h2>
        <p><?= e(t('service_aside_p')) ?></p>
        <a class="btn btn--light btn--block" href="<?= e(url('/rezervacia?sluzba=' . $service['slug'])) ?>"><?= icon('calendar') ?> Objednať sa</a>
        <a class="btn btn--ghost-light btn--block" href="tel:<?= e(cfg('phone_href')) ?>"><?= icon('phone') ?> <?= e(cfg('phone')) ?></a>
      </div>
      <div class="aside__card">
        <h2>Ďalšie služby</h2>
        <ul class="aside__links">
          <?php foreach ($others as $o): ?>
            <li><a href="<?= e(url('/sluzby/' . $o['slug'])) ?>"><?= e($o['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </aside>
  </div>
</section>
