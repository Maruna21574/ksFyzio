<?php
meta([
    'title' => 'Vek psa v ľudských rokoch – kalkulačka',
    'description' => 'Zistite, koľko by mal váš pes v ľudských rokoch a v akej životnej fáze sa nachádza. Rýchla kalkulačka podľa veku a veľkosti psa.',
]);
$headTitle = 'Koľko má váš pes v ľudských rokoch?';
$headLead = 'Zadajte vek a veľkosť psa a zistite, v akej životnej fáze sa nachádza.';
include dirname(__DIR__) . '/partials/page-head.php';
?>
<section class="section section--flush">
  <div class="container container--narrow">
    <form class="agecalc" data-agecalc onsubmit="return false">
      <div class="agecalc__fields">
        <div class="field">
          <label for="age-years">Vek psa (roky)</label>
          <input id="age-years" type="number" inputmode="decimal" min="0" max="30" step="0.5" value="8">
        </div>
        <fieldset class="agecalc__size">
          <legend>Veľkosť psa</legend>
          <label><input type="radio" name="size" value="small"><span><strong>Malý</strong><small>do 10 kg</small></span></label>
          <label><input type="radio" name="size" value="medium" checked><span><strong>Stredný</strong><small>10 – 25 kg</small></span></label>
          <label><input type="radio" name="size" value="large"><span><strong>Veľký</strong><small>nad 25 kg</small></span></label>
        </fieldset>
      </div>

      <div class="agecalc__result" aria-live="polite">
        <p class="agecalc__label">V ľudských rokoch</p>
        <p class="agecalc__big"><strong data-age-human>–</strong><span> rokov</span></p>
        <p class="agecalc__stage" data-age-stage></p>
        <p data-age-text></p>
        <div class="btn-row">
          <a class="btn btn--primary" href="<?= e(url('/rezervacia')) ?>" data-age-cta><?= icon('calendar') ?> Objednať sa</a>
          <a class="btn btn--outline" href="<?= e(url('/test-pohybu')) ?>"><?= icon('activity') ?> Test pohybu psa</a>
        </div>
      </div>
      <p class="notice"><?= icon('shield') ?> <span>Prepočet je orientačný. Každý pes starne inak podľa plemena, zdravia a životného štýlu.</span></p>
    </form>
  </div>
</section>
