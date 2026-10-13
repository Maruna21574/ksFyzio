<?php
meta([
    'title' => 'Príbehy psov – pred a po terapii',
    'description' => 'Príbehy psov, ktorým fyzioterapia pomohla vrátiť sa k pohybu. Porovnanie pred a po.',
]);
if (cfg('demo_content')) {
    meta(['noindex' => true]);
}
$headTitle = 'Príbehy psov';
$headLead = 'Ako sa psom po terapii zlepšil pohyb. Posuňte posuvník a porovnajte stav pred a po.';
include dirname(__DIR__) . '/partials/page-head.php';
include dirname(__DIR__) . '/partials/demo-notice.php';
?>
<section class="section section--flush">
  <div class="container">
    <div class="stories">
      <?php foreach ($GLOBALS['STORIES'] as $i => $s): ?>
        <article class="story" data-reveal>
          <div class="compare" data-compare style="--pos:50%">
            <img class="compare__img compare__img--after" src="<?= e(asset($s['after'])) ?>" alt="<?= e($s['alt']) ?> – po terapii" width="800" height="1067" loading="lazy" decoding="async">
            <img class="compare__img compare__img--before<?= cfg('demo_content') ? ' is-demo' : '' ?>" src="<?= e(asset($s['before'])) ?>" alt="" width="800" height="1067" loading="lazy" decoding="async" aria-hidden="true">
            <span class="compare__tag compare__tag--before">Pred</span>
            <span class="compare__tag compare__tag--after">Po</span>
            <span class="compare__handle" aria-hidden="true"></span>
            <input class="compare__range" type="range" min="0" max="100" value="50" aria-label="Porovnanie pred a po – <?= e($s['name']) ?>">
          </div>

          <div class="story__body">
            <p class="eyebrow"><?= icon('paw') ?> <?= e($s['meta']) ?></p>
            <h2><?= e($s['name']) ?></h2>
            <dl class="story__facts">
              <div><dt>Problém</dt><dd><?= e($s['problem']) ?></dd></div>
              <div><dt>Čo sme robili</dt><dd><?= e($s['therapy']) ?></dd></div>
              <div><dt>Výsledok</dt><dd><?= e($s['result']) ?></dd></div>
            </dl>
            <blockquote>„<?= e($s['quote']) ?>“</blockquote>
            <p class="story__weeks"><?= icon('clock') ?> Spolupráca: približne <?= (int) $s['weeks'] ?> týždňov</p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="notice"><?= icon('shield') ?> <span>Každý pes je iný a výsledky sa môžu líšiť. Príbehy zverejňujeme so súhlasom majiteľov.</span></p>
  </div>
</section>
<?php
$ctaTitle = 'Pomôžme aj vášmu psovi';
include dirname(__DIR__) . '/partials/cta.php';
?>
