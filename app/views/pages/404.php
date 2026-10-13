<?php meta(['title' => 'Stránka sa nenašla']); ?>
<section class="section notfound">
  <div class="container container--narrow center-text">
    <span class="notfound__paw"><?= icon('paw') ?></span>
    <p class="eyebrow eyebrow--center">Chyba 404</p>
    <h1>Tu sme sa asi stratili…</h1>
    <p class="lead">Stránku, ktorú hľadáte, sme nenašli. Možno ju niekto odniesol ako hračku.</p>
    <p class="btn-row btn-row--center">
      <a class="btn btn--primary" href="<?= e(url('/')) ?>">Späť na úvod</a>
      <a class="btn btn--outline" href="<?= e(url('/kontakt')) ?>">Kontakt</a>
    </p>
  </div>
</section>
