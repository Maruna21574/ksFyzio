<?php
// Prístroje a techniky. Voliteľné: $equipmentTitle, $equipmentTint (true = podfarbená sekcia).
$offer = $GLOBALS['OFFER'];
?>
<section class="section<?= !empty($equipmentTint) ? ' section--tint' : '' ?>" aria-labelledby="equipment-h">
  <div class="container">
    <header class="section__head">
      <p class="eyebrow eyebrow--center"><?= e(t('equipment_eyebrow')) ?></p>
      <h2 id="equipment-h"><?= e($equipmentTitle ?? t('equipment_h2')) ?></h2>
      <p><?= e(t('equipment_lead')) ?></p>
    </header>
    <div class="grid grid--3">
      <?php foreach ($offer['devices'] as [$t, $d, $ico, $color]): ?>
        <article class="card card--value card--<?= e($color) ?>" data-reveal>
          <span class="card__icon"><?= icon($ico) ?></span>
          <h3><?= e($t) ?></h3>
          <p><?= e($d) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
    <?php if (!empty($offer['techniques'])): ?>
    <h3 class="center-text" style="margin:2.5rem 0 1rem"><?= e(t('equipment_tech_h3')) ?></h3>
    <ul class="chips" style="justify-content:center">
      <?php foreach ($offer['techniques'] as $t): ?><li><?= icon('check') ?> <?= e($t) ?></li><?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</section>
