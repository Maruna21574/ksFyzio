<?php
// Komu pomáhame. Voliteľné: $patientsTitle, $patientsLead.
?>
<section class="section<?= ($patientsTint ?? true) ? " section--tint" : "" ?>" aria-labelledby="patients-h">
  <div class="container">
    <header class="section__head">
      <p class="eyebrow eyebrow--center">Komu pomáhame</p>
      <h2 id="patients-h"><?= e($patientsTitle ?? 'Pre ktoré zvieratá sme tu') ?></h2>
      <p><?= e($patientsLead ?? 'Venujeme sa psom, mačkám aj ďalším zvieratám v každom veku. Pomoc nie je len pri probléme: správna prevencia môže pomôcť predísť ťažkostiam, ktoré by neskôr vyžadovali náročnejšiu liečbu.') ?></p>
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
