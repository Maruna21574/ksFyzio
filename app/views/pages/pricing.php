<?php
meta([
    'title' => 'Cenník fyzioterapie pre zvieratá',
    'description' => t('pricing_meta'),
]);
$headTitle = t('pricing_h1');
$headLead = t('pricing_lead');
include dirname(__DIR__) . '/partials/page-head.php';
$pricing = $GLOBALS['PRICING'];
?>
<section class="section section--flush">
  <div class="container">
    <?php if (!empty($pricing['groups'])): ?>
    <div class="price-groups">
      <?php foreach ($pricing['groups'] as $g): ?>
        <article class="price-group" data-reveal>
          <h2><?= e($g['title']) ?></h2>
          <ul class="price-rows">
            <?php foreach ($g['rows'] as [$rowName, $rowPrice]): ?>
              <li><span><?= e($rowName) ?></span><strong><?= e($rowPrice) ?></strong></li>
            <?php endforeach; ?>
          </ul>
          <?php if (!empty($g['note'])): ?><p class="price-group__note"><?= icon('shield') ?> <span><?= e($g['note']) ?></span></p><?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($pricing['items'])): ?>
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
    <?php endif; ?>

    <ul class="price-notes">
      <?php foreach ($pricing['notes'] ?? [] as $n): ?><li><?= icon('shield') ?> <span><?= e($n) ?></span></li><?php endforeach; ?>
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
