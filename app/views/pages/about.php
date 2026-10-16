<?php
meta([
    'title' => t('about_h1'),
    'description' => t('about_meta'),
]);
$headTitle = t('about_h1');
$headLead = t('about_lead');
include dirname(__DIR__) . '/partials/page-head.php';
?>
<section class="section section--flush">
  <div class="container split">
    <div class="split__text prose">
      <h2><?= e(t('about_h2')) ?></h2>
      <p><?= th('about_p1') ?></p>
      <p><?= th('about_p2') ?></p>
      <p><?= th('about_p3') ?></p>
    </div>
    <div class="split__media">
      <figure class="photo-card photo-card--tall">
        <img src="<?= e(asset(site_img('about'))) ?>" alt="<?= e(t('about_photo_alt')) ?>" width="800" height="1085" loading="lazy" decoding="async">
      </figure>
    </div>
  </div>
</section>

<?php $person = cfg('therapist'); if (!empty($person['bio'])): ?>
<section class="section" aria-labelledby="therapist-h">
  <div class="container split">
    <?php if (!empty($person['photo'])): ?>
    <div class="split__media">
      <figure class="photo-card photo-card--tall">
        <img src="<?= e(asset($person['photo'])) ?>" alt="<?= e($person['name'] ?: 'Terapeutka K&S Fyzio') ?>" width="800" height="1000" loading="lazy" decoding="async">
      </figure>
    </div>
    <?php endif; ?>
    <div class="split__text prose">
      <p class="eyebrow"><?= e(t('about_person_eyebrow')) ?></p>
      <h2 id="therapist-h"><?= e($person['name'] ?: 'O mne') ?></h2>
      <?php if (!empty($person['role'])): ?><p><strong><?= e($person['role']) ?></strong></p><?php endif; ?>
      <?php foreach ($person['bio'] as $p): ?><p><?= e($p) ?></p><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section section--tint">
  <div class="container">
    <header class="section__head">
      <h2><?= e(t('about_values_h2')) ?></h2>
    </header>
    <div class="grid grid--3">
      <?php foreach ([[1, 'heart', 'cyan'], [2, 'search', 'green'], [3, 'shield', 'red']] as [$n, $ico, $color]): ?>
      <article class="card card--value card--<?= $color ?>" data-reveal>
        <span class="card__icon"><?= icon($ico) ?></span>
        <h3><?= e(t("value{$n}_t")) ?></h3>
        <p><?= e(t("value{$n}_p")) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container prose prose--narrow center-text">
    <h2><?= e(t('about_help_h2')) ?></h2>
    <p><?= e(t('about_help_p')) ?></p>
    <p><a class="btn btn--primary" href="<?= e(url('/rezervacia')) ?>">Objednať sa</a></p>
  </div>
</section>
