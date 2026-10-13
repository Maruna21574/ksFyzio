<?php
meta([
    'title' => 'Cenník fyzioterapie pre psov',
    'description' => 'Orientačný cenník veterinárnej fyzioterapie: vstupné vyšetrenie, jednotlivé terapie, balíčky a výjazd k vám domov.',
]);
if (cfg('demo_content')) {
    meta(['noindex' => true]);
}
$headTitle = 'Cenník';
$headLead = 'Jasné a férové ceny. Presnú sumu vám poviem vždy ešte pred návštevou.';
include dirname(__DIR__) . '/partials/page-head.php';
include dirname(__DIR__) . '/partials/demo-notice.php';
$pricing = $GLOBALS['PRICING'];
?>
<section class="section section--flush">
  <div class="container">
    <div class="pricing">
      <?php foreach ($pricing['items'] as $p): ?>
        <article class="price-card<?= $p['featured'] ? ' price-card--featured' : '' ?>" data-reveal>
          <?php if ($p['featured']): ?><span class="price-card__badge">Najvýhodnejšie</span><?php endif; ?>
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
          <a class="btn <?= $p['featured'] ? 'btn--primary' : 'btn--outline' ?> btn--block" href="<?= e(url('/rezervacia')) ?>">Objednať sa</a>
        </article>
      <?php endforeach; ?>
    </div>

    <ul class="price-notes">
      <?php foreach ($pricing['notes'] as $n): ?><li><?= icon('shield') ?> <span><?= e($n) ?></span></li><?php endforeach; ?>
    </ul>
  </div>
</section>
<?php
$ctaTitle = 'Máte otázku k cene?';
$ctaText = 'Zavolajte alebo napíšte. Radi vám poviem, čo bude váš pes potrebovať.';
include dirname(__DIR__) . '/partials/cta.php';
?>
