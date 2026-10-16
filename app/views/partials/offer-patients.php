<?php
// Komu pomáhame. Voliteľné: $patientsTint (false = biele pozadie).
?>
<section class="section<?= ($patientsTint ?? true) ? ' section--tint' : '' ?>" aria-labelledby="patients-h">
  <div class="container">
    <header class="section__head">
      <p class="eyebrow eyebrow--center"><?= e(t('patients_eyebrow')) ?></p>
      <h2 id="patients-h"><?= e(t('patients_h2')) ?></h2>
      <p><?= e(t('patients_lead')) ?></p>
    </header>
    <div class="grid grid--3">
      <?php foreach ($GLOBALS['OFFER']['patients'] as [$t, $d, $ico, $color]): ?>
        <article class="card card--value card--<?= e($color) ?>" data-reveal>
          <span class="card__icon"><?= icon($ico) ?></span>
          <h3><?= e($t) ?></h3>
          <p><?= e($d) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
