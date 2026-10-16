<?php
meta([
    'title' => 'Tipy a rady pre majiteľov zvierat',
    'description' => t('tips_meta'),
]);
$headTitle = t('tips_h1');
$headLead = t('tips_lead');
include dirname(__DIR__) . '/partials/page-head.php';
?>
<section class="section section--flush">
  <div class="container">
    <div class="grid grid--3">
      <?php foreach ($GLOBALS['ARTICLES'] as $a): ?>
        <a class="card card--article" href="<?= e(url('/tipy/' . $a['slug'])) ?>" data-reveal>
          <?php if ($a['image']): ?>
            <img src="<?= e(asset($a['image'])) ?>" alt="<?= e($a['image_alt']) ?>" width="900" height="900" loading="lazy" decoding="async">
          <?php else: ?>
            <span class="card--article__ph" aria-hidden="true"><?= icon('paw') ?></span>
          <?php endif; ?>
          <div class="card__body">
            <span class="card__meta"><?= icon('clock') ?> <?= (int) $a['minutes'] ?> min čítania</span>
            <h2><?= e($a['title']) ?></h2>
            <p><?= e($a['excerpt']) ?></p>
            <span class="card__more">Čítať <?= icon('arrow') ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include dirname(__DIR__) . '/partials/cta.php'; ?>
