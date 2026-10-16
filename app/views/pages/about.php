<?php
meta([
    'title' => 'O nás',
    'description' => 'K&S Fyzio – veterinárna fyzioterapia pre psov, mačky a iné zvieratá so sídlom v Seredi. Posúvame hranice pohybu s individuálnym a šetrným prístupom.',
]);
$headTitle = 'O nás';
$headLead = 'Posúvame hranice pohybu – pre zvieratá, ktoré sa chcú znova hýbať s radosťou.';
include dirname(__DIR__) . '/partials/page-head.php';
?>
<section class="section section--flush">
  <div class="container split">
    <div class="split__text prose">
      <h2>Kto sme</h2>
      <p>K&amp;S Fyzio je veterinárna fyzioterapia pre psov, mačky a ďalšie zvieratá vrátane koní so sídlom v Seredi. Pôsobíme v Trnavskom a Bratislavskom kraji a za našimi štvornohými klientmi chodíme priamo k nim domov. Terapiu dopĺňame prístrojmi (laser, magnetoterapia, elektroterapia) a pripravujeme vlastnú ambulanciu s hydroterapiou, rázovou vlnou a bežeckým pásom.</p>
      <p>Veríme, že pohyb je pre každé zviera základ kvality života. Preto si všímame aj drobné zmeny – ťažšie vstávanie, kratšiu prechádzku či vyhýbanie sa schodom – a pomáhame hľadať spôsob, ako sa im postaviť. Nezameriavame sa len na riešenie problémov, ale aj na prevenciu a včasnú podporu pohybu.</p>
      <p>Naše heslo <strong>„Posúvame hranice pohybu“</strong> hovorí, o čo nám ide: aby sa zviera po úraze, operácii, v športe aj v seniorskom veku mohlo hýbať čo najviac a čo najpohodlnejšie.</p>
    </div>
    <div class="split__media">
      <figure class="photo-card photo-card--tall">
        <img src="<?= e(asset('assets/img/praxe-odmena.webp')) ?>" alt="Pes sa načahuje za pamlskom, ktorý mu ponúka ruka terapeuta" width="800" height="1085" loading="lazy" decoding="async">
      </figure>
    </div>
  </div>
</section>

<?php $t = cfg("therapist"); if (!empty($t["bio"])): ?>
<section class="section" aria-labelledby="therapist-h">
  <div class="container split">
    <?php if (!empty($t["photo"])): ?>
    <div class="split__media">
      <figure class="photo-card photo-card--tall">
        <img src="<?= e(asset($t["photo"])) ?>" alt="<?= e($t["name"] ?: "Terapeutka K&S Fyzio") ?>" width="800" height="1000" loading="lazy" decoding="async">
      </figure>
    </div>
    <?php endif; ?>
    <div class="split__text prose">
      <p class="eyebrow">Kto sa o vaše zviera postará</p>
      <h2 id="therapist-h"><?= e($t["name"] ?: "O mne") ?></h2>
      <?php if (!empty($t["role"])): ?><p><strong><?= e($t["role"]) ?></strong></p><?php endif; ?>
      <?php foreach ($t["bio"] as $p): ?><p><?= e($p) ?></p><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php $t = cfg("therapist"); if (!empty($t["bio"])): ?>
<section class="section section--tint" aria-labelledby="therapist-h">
  <div class="container split">
    <?php if (!empty($t["photo"])): ?>
    <div class="split__media">
      <figure class="photo-card photo-card--tall">
        <img src="<?= e(asset($t["photo"])) ?>" alt="<?= e($t["name"] ?: "Terapeutka K&S Fyzio") ?>" width="800" height="1000" loading="lazy" decoding="async">
      </figure>
    </div>
    <?php endif; ?>
    <div class="split__text prose">
      <p class="eyebrow">Kto sa o vaše zviera postará</p>
      <h2 id="therapist-h"><?= e($t["name"] ?: "O mne") ?></h2>
      <?php if (!empty($t["role"])): ?><p><strong><?= e($t["role"]) ?></strong></p><?php endif; ?>
      <?php foreach ($t["bio"] as $p): ?><p><?= e($p) ?></p><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section section--tint">
  <div class="container">
    <header class="section__head">
      <h2>Na čom si zakladáme</h2>
    </header>
    <div class="grid grid--3">
      <article class="card card--value card--cyan" data-reveal>
        <span class="card__icon"><?= icon('heart') ?></span>
        <h3>Šetrný prístup</h3>
        <p>Pracujeme v tempe, ktoré je pre zviera príjemné. Pri terapii sa má cítiť bezpečne.</p>
      </article>
      <article class="card card--value card--green" data-reveal>
        <span class="card__icon"><?= icon('search') ?></span>
        <h3>Individuálny prístup</h3>
        <p>Každé zviera je iné. Postup vždy prispôsobíme jeho veku, kondícii, ťažkostiam aj povahe.</p>
      </article>
      <article class="card card--value card--red" data-reveal>
        <span class="card__icon"><?= icon('shield') ?></span>
        <h3>Spolupráca s veterinárom</h3>
        <p>Fyzioterapia dopĺňa veterinárnu starostlivosť. Vždy dbáme na odporúčania ošetrujúceho veterinára.</p>
      </article>
    </div>
  </div>
</section>

<section class="section">
  <div class="container prose prose--narrow center-text">
    <h2>Pomáhame aj vám</h2>
    <p>Terapia je len časť cesty. Preto vám radi vysvetlíme, čo sme pri vyšetrení zistili, a ukážeme jednoduché kroky, ktorými môžete zvieraťu pomáhať doma – od úpravy prostredia až po vhodnú záťaž.</p>
    <p><a class="btn btn--primary" href="<?= e(url('/rezervacia')) ?>">Objednať sa</a></p>
  </div>
</section>
