<?php
meta([
    'description' => 'K&S Fyzio – veterinárna fyzioterapia pre psov, mačky a ďalšie zvieratá vrátane koní. Rehabilitácie, uvoľnenie svalov, laser, magnetoterapia a elektroterapia. Výjazdy domov v Trnavskom a Bratislavskom kraji.',
]);
$services = $GLOBALS['SERVICES'];
?>
<section class="hero">
  <div class="container hero__inner">
    <div class="hero__text">
      <p class="eyebrow"><?= icon('paw') ?> Veterinárna fyzioterapia pre psov, mačky a iné zvieratá</p>
      <h1>Pomáhame zvieratám <span class="hl">hýbať sa bez bolesti</span></h1>
      <p class="lead">Rehabilitácie, uvoľnenie svalov a podpora pohybu pre psov, mačky aj iné zvieratá, napríklad kone. Individuálny prístup, prístrojová terapia a výjazd k vám domov.</p>
      <div class="hero__actions">
        <a class="btn btn--primary btn--lg" href="<?= e(url('/rezervacia')) ?>"><?= icon('calendar') ?> Objednať sa</a>
        <a class="btn btn--outline btn--lg" href="tel:<?= e(cfg('phone_href')) ?>"><?= icon('phone') ?> <?= e(cfg('phone')) ?></a>
      </div>
      <ul class="hero__facts">
        <li><?= icon('home') ?> Výjazd k vám domov</li>
        <li><?= icon('pin') ?> Trnavský a Bratislavský kraj</li>
        <li><?= icon('heart') ?> Individuálny prístup</li>
      </ul>
    </div>
    <div class="hero__media">
      <div class="hero__photo">
        <img src="<?= e(asset('assets/img/hero-dog.webp')) ?>" srcset="<?= e(asset('assets/img/hero-dog-640.webp')) ?> 520w, <?= e(asset('assets/img/hero-dog.webp')) ?> 900w" sizes="(min-width: 992px) 480px, 90vw" alt="Spokojný pes pri fyzioterapii – terapeutka mu šetrne kladie ruku na bok" width="900" height="1027" fetchpriority="high" decoding="async">
      </div>
      <div class="hero__badge"><?= icon('heart') ?><span><strong>Posúvame</strong> hranice pohybu</span></div>
    </div>
  </div>
</section>

<section class="section" aria-labelledby="sluzby-h">
  <div class="container">
    <header class="section__head">
      <p class="eyebrow eyebrow--center">Čomu sa venujeme</p>
      <h2 id="sluzby-h">Naše služby</h2>
      <p>Odborná starostlivosť o pohyb vášho zvieraťa – od rehabilitácie po prevenciu, doplnená prístrojovou terapiou a technikami uvoľnenia.</p>
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
include dirname(__DIR__) . "/partials/offer-patients.php";
?>

<section class="section section--tint talk" aria-labelledby="talk-h">
  <div class="container talk__inner">
    <div class="talk__text" data-reveal>
      <p class="eyebrow"><?= icon('paw') ?> Všímajte si ich pohyb</p>
      <h2 id="talk-h">Keby psy vedeli <span class="hl hl--green">rozprávať</span><span class="hl hl--red">…</span></h2>
      <p>Naši štvornohí parťáci nám síce nevedia povedať, čo ich trápi, ale ich pohyb a správanie nám môžu veľa napovedať. Všimli ste si niektorú z týchto zmien?</p>
      <ul class="checks">
        <li><?= icon('check') ?> Vstáva ťažšie ako kedysi?</li>
        <li><?= icon('check') ?> Nechce už toľko behať a skákať?</li>
        <li><?= icon('check') ?> Všimli ste si zmenu v jeho chôdzi?</li>
      </ul>
      <p><strong>Možno je čas venovať jeho pohybu trochu viac pozornosti.</strong></p>
      <div class="btn-row">
        <a class="btn btn--primary" href="<?= e(url('/rezervacia')) ?>">Objednať sa</a>
        <a class="btn btn--outline" href="<?= e(url('/test-pohybu')) ?>"><?= icon('activity') ?> Otestujte svojho psa</a>
      </div>
    </div>
    <div class="bubbles" aria-hidden="true">
      <p class="bubble bubble--green" data-reveal>Panička, dnes sa mi ťažšie vstáva.</p>
      <p class="bubble bubble--red" data-reveal>Ten gauč je zrazu nejako vysoko!</p>
      <p class="bubble bubble--blue" data-reveal>Dnes by som si prechádzku dal radšej kratšiu.</p>
      <p class="bubble bubble--sand" data-reveal>A tie schody? Dnes by som ich radšej vynechal.</p>
    </div>
  </div>
</section>

<section class="section" aria-labelledby="home-h">
  <div class="container split">
    <div class="split__media" data-reveal>
      <div class="mascot-stage">
        <img src="<?= e(asset('assets/img/mascot.webp')) ?>" alt="Maskot K&amp;S Fyzio – veselý pes so šatkou" width="760" height="1061" loading="lazy" decoding="async">
        <p class="mascot-stage__text"><strong>Bez stresu. Bez cestovania.</strong> V pohodlí vášho domova.</p>
      </div>
    </div>
    <div class="split__text" data-reveal>
      <p class="eyebrow">Výjazd k vám domov</p>
      <h2 id="home-h">Odborná starostlivosť v prostredí, kde sa vaše zviera cíti bezpečne</h2>
      <ul class="checks checks--grid">
        <li><?= icon('check') ?> Diagnostika</li>
        <li><?= icon('check') ?> Vyšetrenie</li>
        <li><?= icon('check') ?> Fyzioterapia</li>
        <li><?= icon('check') ?> Individuálny prístup</li>
      </ul>
      <p>Pripravujeme aj vlastnú ambulanciu s hydroterapiou a bežeckým pásom. Zatiaľ za vami prídeme domov.</p>
      <p>Pôsobíme v Trnavskom a Bratislavskom kraji so základňou v Seredi. Objednať sa môžete formulárom, telefonicky alebo cez WhatsApp, Messenger, Instagram či Facebook.</p>
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
      <p class="eyebrow eyebrow--center">Jednoducho a bez stresu</p>
      <h2 id="steps-h">Ako to funguje</h2>
    </header>
    <ol class="steps">
      <li class="step" data-reveal>
        <span class="step__num">1</span>
        <h3>Objednáte sa</h3>
        <p>Vyplníte formulár alebo nám zavoláte. Ozveme sa vám a dohodneme termín.</p>
      </li>
      <li class="step" data-reveal>
        <span class="step__num">2</span>
        <h3>Vyšetrenie</h3>
        <p>Posúdime pohyb a stav vášho zvieraťa a porozprávame sa o tom, čo ho trápi.</p>
      </li>
      <li class="step" data-reveal>
        <span class="step__num">3</span>
        <h3>Terapia a plán</h3>
        <p>Prebehne terapia a dostanete odporúčania, ako zvieraťu pomáhať aj doma.</p>
      </li>
    </ol>
    <p class="center"><a class="btn btn--outline" href="<?= e(url('/ako-to-funguje')) ?>">Podrobnejšie a časté otázky</a></p>
  </div>
</section>

<section class="section" aria-labelledby="area-h">
  <div class="container area">
    <div>
      <p class="eyebrow">Kde pôsobíme</p>
      <h2 id="area-h">Trnavský a Bratislavský kraj</h2>
      <p>Sídlime v Seredi a za pohybom vášho psa vyrážame po celom Trnavskom a Bratislavskom kraji. Ak si nie ste istí, či k vám zvládneme prísť, jednoducho sa opýtajte.</p>
    </div>
    <ul class="chips">
      <?php foreach (cfg('area') as $a): ?><li><?= icon('pin') ?> <?= e($a) ?></li><?php endforeach; ?>
    </ul>
  </div>
</section>

<?php
$ctaTitle = 'Dajte svojmu zvieraťu pohyb bez bolesti';
include dirname(__DIR__) . '/partials/cta.php';
?>
