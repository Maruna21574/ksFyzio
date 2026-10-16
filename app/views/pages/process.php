<?php
meta([
    'title' => 'Ako to funguje a časté otázky',
    'description' => t('process_meta'),
]);
$headTitle = t('process_h1');
$headLead = t('process_lead');
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
      <?php for ($i = 1; $i <= 4; $i++): ?>
      <li class="step" data-reveal>
        <span class="step__num"><?= $i ?></span>
        <h3><?= e(t("pstep{$i}_t")) ?></h3>
        <p><?= e(t("pstep{$i}_p")) ?></p>
      </li>
      <?php endfor; ?>
    </ol>
  </div>
</section>

<section class="section section--tint" aria-labelledby="faq-h">
  <div class="container container--narrow">
    <header class="section__head">
      <h2 id="faq-h"><?= e(t('faq_h2')) ?></h2>
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
