<?php
meta([
    'title' => 'Cenník fyzioterapie pre zvieratá',
    'description' => t('pricing_meta'),
]);
if (cfg('demo_content')) {
    meta(['noindex' => true]);
}
$headTitle = t('pricing_h1');
$headLead = t('pricing_lead');
include dirname(__DIR__) . '/partials/page-head.php';
include dirname(__DIR__) . '/partials/demo-notice.php';
$pricing = $GLOBALS['PRICING'];
?>
<section class="section section--flush">
  <div class="container">
    <div class="pricing">
      <?php foreach ($pricing['items'] as $p): ?>
        <article class="price-card<?= !empty($p['featured']) ? ' price-card--featured' : '' ?>" data-reveal>
          <?php if (!empty($p['featured'])): ?><span class="price-card__badge"><?= e(t('pricing_badge')) ?></span><?php endif; ?>
          <h2><?= e($p['title']) ?></h2>
          <p class="price-card__text"><?= e($p['text']) ?></p>
          <p class="price-card__price">
            <?php if ($p['price'] !== ''): ?>
              <strong><?= e($p['price']) ?></strong> <span><?= e($p['unit']) ?></span>
            <?php else: ?>
              <strong class="price-card__ask">Cena na vyžiadanie</strong>
            <?php endif; ?>
          </p>
          <ul class="checks">
            <?php foreach ($p['includes'] as $inc): ?><li><?= icon('check') ?> <?= e($inc) ?></li><?php endforeach; ?>
          </ul>
          <a class="btn <?= !empty($p['featured']) ? 'btn--primary' : 'btn--outline' ?> btn--block" href="<?= e(url('/rezervacia')) ?>">Objednať sa</a>
        </article>
      <?php endforeach; ?>
    </div>

    <ul class="price-notes">
      <?php foreach ($pricing['notes'] as $n): ?><li><?= icon('shield') ?> <span><?= e($n) ?></span></li><?php endforeach; ?>
    </ul>
  </div>
</section>
<?php
$equipmentTitle = t('pricing_equipment_h2');
$equipmentTint = true;
include dirname(__DIR__) . '/partials/offer-equipment.php';
$ctaTitle = t('pricing_cta_title');
$ctaText = t('pricing_cta_text');
include dirname(__DIR__) . '/partials/cta.php';
?>
