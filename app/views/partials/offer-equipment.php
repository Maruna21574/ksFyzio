<?php
// Prístroje a techniky. Voliteľné: $equipmentTitle, $equipmentTint (true = podfarbená sekcia).
$offer = $GLOBALS['OFFER'];
?>
<section class="section<?= !empty($equipmentTint) ? ' section--tint' : '' ?>" aria-labelledby="equipment-h">
  <div class="container">
    <header class="section__head">
      <p class="eyebrow eyebrow--center">Čím pracujeme</p>
      <h2 id="equipment-h"><?= e($equipmentTitle ?? 'Prístroje a techniky') ?></h2>
      <p>Manuálnu terapiu dopĺňame prístrojmi. Postup a kombináciu vždy vyberáme podľa konkrétneho zvieraťa a jeho ťažkostí.</p>
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
    <h3 class="center-text" style="margin:2.5rem 0 1rem">Techniky a metódy</h3>
    <ul class="chips" style="justify-content:center">
      <?php foreach ($offer['techniques'] as $t): ?><li><?= icon('check') ?> <?= e($t) ?></li><?php endforeach; ?>
    </ul>
  </div>
</section>
