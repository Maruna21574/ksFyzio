<?php
meta([
    'title' => 'Služby – fyzioterapia pre zvieratá',
    'description' => t('services_meta'),
]);
$headTitle = t('services_h1');
$headLead = t('services_lead');
include dirname(__DIR__) . '/partials/page-head.php';
?>
<section class="section section--flush">
  <div class="container">
    <div class="grid grid--services">
      <?php foreach ($GLOBALS['SERVICES'] as $s): ?>
        <a class="card card--service card--<?= e($s['color']) ?>" href="<?= e(url('/sluzby/' . $s['slug'])) ?>" data-reveal>
          <span class="card__icon"><?= icon($s['icon']) ?></span>
          <h3><?= e($s['title']) ?></h3>
          <p><?= e($s['short']) ?></p>
          <span class="card__more">Zistiť viac <?= icon('arrow') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php
include dirname(__DIR__) . '/partials/offer-patients.php';
$equipmentTint = false;
include dirname(__DIR__) . '/partials/offer-equipment.php';
include dirname(__DIR__) . '/partials/cta.php';
?>
