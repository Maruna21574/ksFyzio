<?php
meta([
    'title' => 'Test pohybu – všíma si váš pes zmeny?',
    'description' => 'Rýchly test v 8 otázkach: zistite, či signály, ktoré si všímate u svojho psa, môžu znamenať ťažkosti s pohybom.',
]);
$headTitle = 'Test pohybu psa';
$headLead = 'Označte, čo ste si u svojho psa všimli. Test je orientačný a trvá pol minúty.';
include dirname(__DIR__) . '/partials/page-head.php';
$signals = $GLOBALS['SIGNALS'];
?>
<section class="section section--flush">
  <div class="container container--narrow">
    <form class="quiz" data-quiz data-base="<?= e(url('/rezervacia')) ?>" onsubmit="return false">
      <fieldset>
        <legend class="sr-only">Signály u vášho psa</legend>
        <?php foreach ($signals as $i => $text): ?>
          <label class="quiz__item">
            <input type="checkbox" name="s" value="<?= (int) $i ?>">
            <span class="quiz__box"><?= icon('check') ?></span>
            <span class="quiz__text"><?= e($text) ?></span>
          </label>
        <?php endforeach; ?>
      </fieldset>

      <div class="quiz__result" data-quiz-result aria-live="polite">
        <div class="quiz__meter" aria-hidden="true"><span data-quiz-bar></span></div>
        <p class="quiz__count"><strong data-quiz-count>0</strong> z <?= count($signals) ?> signálov</p>
        <h2 data-quiz-title>Označte, čo ste si všimli</h2>
        <p data-quiz-text>Po označení signálov tu uvidíte odporúčanie.</p>
        <div class="btn-row">
          <a class="btn btn--primary" href="<?= e(url('/rezervacia')) ?>" data-quiz-book><?= icon('calendar') ?> Objednať sa</a>
          <a class="btn btn--outline" href="tel:<?= e(cfg('phone_href')) ?>"><?= icon('phone') ?> <?= e(cfg('phone')) ?></a>
        </div>
      </div>
      <p class="notice"><?= icon('shield') ?> <span>Test je len orientačný a nenahrádza vyšetrenie u veterinára. Pri náhlom zhoršení alebo silnej bolesti vyhľadajte veterinára.</span></p>
    </form>
  </div>
</section>
