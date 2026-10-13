<?php
meta([
    'title' => 'Ako to funguje a časté otázky',
    'description' => 'Ako prebieha fyzioterapia psa – od objednania cez prvé vyšetrenie až po terapiu. Odpovede na najčastejšie otázky.',
]);
$headTitle = 'Ako to funguje';
$headLead = 'Od objednania po terapiu – jednoducho, v pokoji a bez stresu pre vás aj vášho psa.';
include dirname(__DIR__) . '/partials/page-head.php';

$faq = $GLOBALS['FAQ'];
$faqSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(fn ($q) => [
        '@type' => 'Question',
        'name' => $q[0],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $q[1]],
    ], $faq),
];
?>
<section class="section section--flush">
  <div class="container">
    <ol class="steps steps--4">
      <li class="step" data-reveal>
        <span class="step__num">1</span>
        <h3>Objednanie</h3>
        <p>Cez formulár, telefonicky alebo správou cez WhatsApp, Messenger, Instagram či Facebook. Ozveme sa a dohodneme termín.</p>
      </li>
      <li class="step" data-reveal>
        <span class="step__num">2</span>
        <h3>Rozhovor a vyšetrenie</h3>
        <p>Spýtame sa, čo ste si všimli, a posúdime pohyb psa, svalstvo a kĺby.</p>
      </li>
      <li class="step" data-reveal>
        <span class="step__num">3</span>
        <h3>Terapia</h3>
        <p>Podľa potreby využijeme manuálne techniky a cvičenia, vždy šetrne a v tempe, ktoré je psovi príjemné.</p>
      </li>
      <li class="step" data-reveal>
        <span class="step__num">4</span>
        <h3>Odporúčania</h3>
        <p>Dostanete jasné rady, ako psovi pomáhať doma, a dohodneme ďalší postup.</p>
      </li>
    </ol>
  </div>
</section>

<section class="section section--tint" aria-labelledby="faq-h">
  <div class="container container--narrow">
    <header class="section__head">
      <h2 id="faq-h">Časté otázky</h2>
    </header>
    <div class="faq">
      <?php foreach ($faq as [$q, $a]): ?>
        <details class="faq__item">
          <summary><?= e($q) ?></summary>
          <p><?= e($a) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<script type="application/ld+json"><?= json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?></script>
<?php include dirname(__DIR__) . '/partials/cta.php'; ?>
