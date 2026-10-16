<?php
meta(['description' => t('home_meta')]);
$services = $GLOBALS['SERVICES'];
?>
<section class="hero">
  <div class="container hero__inner">
    <div class="hero__text">
      <p class="eyebrow"><?= icon('paw') ?> <?= e(t('home_eyebrow')) ?></p>
      <h1><?= th('home_h1') ?></h1>
      <p class="lead"><?= e(t('home_lead')) ?></p>
      <div class="hero__actions">
        <a class="btn btn--primary btn--lg" href="<?= e(url('/rezervacia')) ?>"><?= icon('calendar') ?> Objednať sa</a>
        <a class="btn btn--outline btn--lg" href="tel:<?= e(cfg('phone_href')) ?>"><?= icon('phone') ?> <?= e(cfg('phone')) ?></a>
      </div>
      <ul class="hero__facts">
        <li><?= icon('home') ?> <?= e(t('home_fact1')) ?></li>
        <li><?= icon('pin') ?> <?= e(t('home_fact2')) ?></li>
        <li><?= icon('heart') ?> <?= e(t('home_fact3')) ?></li>
      </ul>
    </div>
    <div class="hero__media">
      <div class="hero__photo">
        <img src="<?= e(asset(site_img('hero'))) ?>" srcset="<?= e(asset(site_img_small('hero'))) ?> 520w, <?= e(asset(site_img('hero'))) ?> 900w" sizes="(min-width: 992px) 480px, 90vw" alt="<?= e(t('home_hero_alt')) ?>" width="900" height="1027" fetchpriority="high" decoding="async">
      </div>
      <div class="hero__badge"><?= icon('heart') ?><span><?= th('home_badge') ?></span></div>
    </div>
  </div>
</section>

<section class="section" aria-labelledby="sluzby-h">
  <div class="container">
    <header class="section__head">
      <p class="eyebrow eyebrow--center"><?= e(t('home_srv_eyebrow')) ?></p>
      <h2 id="sluzby-h"><?= e(t('home_srv_h2')) ?></h2>
      <p><?= e(t('home_srv_lead')) ?></p>
    </header>
    <div class="grid grid--services">
      <?php foreach ($services as $s): ?>
        <a class="card card--service card--<?= e($s['color']) ?>" href="<?= e(url('/sluzby/' . $s['slug'])) ?>" data-reveal>
          <span class="card__icon"><?= icon($s['icon']) ?></span>
          <h3><?= e($s['title']) ?></h3>
          <p><?= e($s['short']) ?></p>
          <span class="card__more">Zistiť viac <?= icon('arrow') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
$patientsTint = false;
include dirname(__DIR__) . '/partials/offer-patients.php';
?>

<section class="section section--tint talk" aria-labelledby="talk-h">
  <div class="container talk__inner">
    <div class="talk__text" data-reveal>
      <p class="eyebrow"><?= icon('paw') ?> <?= e(t('talk_eyebrow')) ?></p>
      <h2 id="talk-h"><?= th('talk_h2') ?></h2>
      <p><?= e(t('talk_p1')) ?></p>
      <ul class="checks">
        <?php foreach (t_lines('talk_checks') as $c): ?><li><?= icon('check') ?> <?= e($c) ?></li><?php endforeach; ?>
      </ul>
      <p><strong><?= e(t('talk_strong')) ?></strong></p>
      <div class="btn-row">
        <a class="btn btn--primary" href="<?= e(url('/rezervacia')) ?>">Objednať sa</a>
        <a class="btn btn--outline" href="<?= e(url('/test-pohybu')) ?>"><?= icon('activity') ?> <?= e(t('talk_btn2')) ?></a>
      </div>
    </div>
    <div class="bubbles" aria-hidden="true">
      <?php foreach (array_slice(t_lines('talk_bubbles'), 0, 4) as $i => $b): ?>
        <p class="bubble bubble--<?= ['green', 'red', 'blue', 'sand'][$i] ?>" data-reveal><?= e($b) ?></p>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" aria-labelledby="home-h">
  <div class="container split">
    <div class="split__media" data-reveal>
      <div class="mascot-stage">
        <img src="<?= e(asset(site_img('mascot'))) ?>" alt="Maskot K&amp;S Fyzio – veselý pes so šatkou" width="760" height="1061" loading="lazy" decoding="async">
        <p class="mascot-stage__text"><?= th('visit_caption') ?></p>
      </div>
    </div>
    <div class="split__text" data-reveal>
      <p class="eyebrow"><?= e(t('visit_eyebrow')) ?></p>
      <h2 id="home-h"><?= e(t('visit_h2')) ?></h2>
      <ul class="checks checks--grid">
        <?php foreach (t_lines('visit_checks') as $c): ?><li><?= icon('check') ?> <?= e($c) ?></li><?php endforeach; ?>
      </ul>
      <p><?= e(t('visit_p1')) ?></p>
      <p><?= e(t('visit_p2')) ?></p>
      <div class="btn-row">
        <a class="btn btn--primary" href="<?= e(url('/sluzby/vyjazd-domov')) ?>">Viac o výjazdoch</a>
        <a class="btn btn--outline" href="<?= e(cfg('whatsapp')) ?>" target="_blank" rel="noopener"><?= icon('chat') ?> Napísať na WhatsApp</a>
      </div>
    </div>
  </div>
</section>

<section class="section section--tint" aria-labelledby="steps-h">
  <div class="container">
    <header class="section__head">
      <p class="eyebrow eyebrow--center"><?= e(t('steps_eyebrow')) ?></p>
      <h2 id="steps-h"><?= e(t('steps_h2')) ?></h2>
    </header>
    <ol class="steps">
      <?php for ($i = 1; $i <= 3; $i++): ?>
      <li class="step" data-reveal>
        <span class="step__num"><?= $i ?></span>
        <h3><?= e(t("step{$i}_t")) ?></h3>
        <p><?= e(t("step{$i}_p")) ?></p>
      </li>
      <?php endfor; ?>
    </ol>
    <p class="center"><a class="btn btn--outline" href="<?= e(url('/ako-to-funguje')) ?>"><?= e(t('steps_btn')) ?></a></p>
  </div>
</section>

<section class="section" aria-labelledby="area-h">
  <div class="container area">
    <div>
      <p class="eyebrow"><?= e(t('area_eyebrow')) ?></p>
      <h2 id="area-h"><?= e(t('area_h2')) ?></h2>
      <p><?= e(t('area_p')) ?></p>
    </div>
    <ul class="chips">
      <?php foreach (cfg('area') as $a): ?><li><?= icon('pin') ?> <?= e($a) ?></li><?php endforeach; ?>
    </ul>
  </div>
</section>

<?php
$ctaTitle = t('home_cta_title');
include dirname(__DIR__) . '/partials/cta.php';
?>
