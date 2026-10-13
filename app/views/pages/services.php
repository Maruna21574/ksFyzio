<?php
meta([
    'title' => 'Služby – fyzioterapia pre psov',
    'description' => 'Rehabilitácie po úrazoch a operáciách, uvoľnenie svalov a regenerácia, podpora pohybu starších psov, vyšetrenie a výjazd domov. K&S Fyzio.',
]);
$headTitle = 'Naše služby';
$headLead = 'Od rehabilitácie po úraze cez regeneráciu až po podporu pohybu starších psov. Každý pes je iný, preto ku každému pristupujeme individuálne.';
include dirname(__DIR__) . '/partials/page-head.php';
?>
<section class="section section--flush">
  <div class="container">
    <div class="grid grid--services">
      <?php foreach ($GLOBALS['SERVICES'] as $s): ?>
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
<?php include dirname(__DIR__) . '/partials/cta.php'; ?>
