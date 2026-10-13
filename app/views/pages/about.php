<?php
meta([
    'title' => 'O nás',
    'description' => 'K&S Fyzio – veterinárna fyzioterapia pre psov so sídlom v Seredi. Posúvame hranice pohybu s individuálnym a šetrným prístupom.',
]);
$headTitle = 'O nás';
$headLead = 'Posúvame hranice pohybu – pre psov, ktorí sa chcú znova hýbať s radosťou.';
include dirname(__DIR__) . '/partials/page-head.php';
?>
<section class="section section--flush">
  <div class="container split">
    <div class="split__text prose">
      <h2>Kto sme</h2>
      <p>K&amp;S Fyzio je veterinárna fyzioterapia pre psov so sídlom v Seredi. Pôsobíme v Trnavskom a Bratislavskom kraji a za našimi štvornohými klientmi chodíme priamo k nim domov.</p>
      <p>Veríme, že pohyb je pre psa základ kvality života. Preto si všímame aj drobné zmeny – ťažšie vstávanie, kratšiu prechádzku či vyhýbanie sa schodom – a pomáhame hľadať spôsob, ako sa im postaviť.</p>
      <p>Naše heslo <strong>„Posúvame hranice pohybu“</strong> hovorí, o čo nám ide: aby sa pes po úraze, operácii alebo v seniorskom veku mohol hýbať čo najviac a čo najpohodlnejšie.</p>
    </div>
    <div class="split__media">
      <figure class="photo-card photo-card--tall">
        <img src="<?= e(asset('assets/img/praxe-odmena.webp')) ?>" alt="Pes sa načahuje za pamlskom, ktorý mu ponúka ruka terapeuta" width="800" height="1085" loading="lazy" decoding="async">
      </figure>
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="container">
    <header class="section__head">
      <h2>Na čom si zakladáme</h2>
    </header>
    <div class="grid grid--3">
      <article class="card card--value card--cyan" data-reveal>
        <span class="card__icon"><?= icon('heart') ?></span>
        <h3>Šetrný prístup</h3>
        <p>Pracujeme v tempe, ktoré je pre psa príjemné. Pes by sa mal pri terapii cítiť bezpečne.</p>
      </article>
      <article class="card card--value card--green" data-reveal>
        <span class="card__icon"><?= icon('search') ?></span>
        <h3>Individuálny prístup</h3>
        <p>Každý pes je iný. Postup vždy prispôsobíme jeho veku, kondícii, ťažkostiam aj povahe.</p>
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
    <p>Terapia je len časť cesty. Preto vám radi vysvetlíme, čo sme pri vyšetrení zistili, a ukážeme jednoduché kroky, ktorými môžete psovi pomáhať doma – od úpravy prostredia až po vhodnú záťaž.</p>
    <p><a class="btn btn--primary" href="<?= e(url('/rezervacia')) ?>">Objednať sa</a></p>
  </div>
</section>
