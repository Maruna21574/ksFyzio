<section class="page-head">
  <div class="container">
    <nav class="crumbs" aria-label="Omrvinková navigácia">
      <a href="<?= e(url('/')) ?>">Domov</a>
      <?php foreach (($crumbs ?? []) as [$cHref, $cLabel]): ?>
        <span aria-hidden="true">/</span><a href="<?= e(url($cHref)) ?>"><?= e($cLabel) ?></a>
      <?php endforeach; ?>
      <span aria-hidden="true">/</span><span aria-current="page"><?= e($headTitle) ?></span>
    </nav>
    <h1><?= e($headTitle) ?></h1>
    <?php if (!empty($headLead)): ?><p class="lead"><?= e($headLead) ?></p><?php endif; ?>
  </div>
</section>
