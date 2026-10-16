<p class="crumbs"><a href="<?= e(url('/admin')) ?>">← Späť na prehľad</a></p>
<h1><?= $page[2] ?> <?= e($page[0]) ?></h1>
<p class="lead"><?= e($page[1]) ?></p>

<?php if ($key === 'texty'): ?>
  <nav class="tabs" aria-label="Skupiny textov">
    <?php foreach (text_groups() as $gk => $g): ?>
      <a href="<?= e(url('/admin/edit/texty?g=' . $gk)) ?>"<?= $gk === $group ? ' class="is-on" aria-current="page"' : '' ?>><?= e($g['title']) ?></a>
    <?php endforeach; ?>
  </nav>
  <p class="tip">Značky: <code>**tučné**</code> · <code>[[zvýraznené]]</code> · <code>[[g:zelené]]</code> · <code>[[r:červené]]</code> (používa sa len v nadpisoch, kde je to už teraz).</p>
<?php endif; ?>

<form method="post" action="<?= e(url($self)) ?>" enctype="multipart/form-data" class="aform">
  <input type="hidden" name="_csrf" value="<?= e(adm_csrf()) ?>">
  <input type="hidden" name="action" value="save">
  <?= adm_fields($fields, $values, 'd', 0) ?>
  <div class="savebar">
    <button class="btn btn--primary btn--lg" type="submit">Uložiť zmeny</button>
    <a class="btn" href="<?= e(url('/')) ?>" target="_blank" rel="noopener">Zobraziť web ↗</a>
  </div>
</form>

<form method="post" action="<?= e(url($self)) ?>" class="reset">
  <input type="hidden" name="_csrf" value="<?= e(adm_csrf()) ?>">
  <input type="hidden" name="action" value="reset">
  <button class="linkbtn linkbtn--danger" data-confirm="Naozaj vrátiť predvolené hodnoty? Vaše úpravy v tejto časti sa stratia (záloha sa uloží na server).">Vrátiť predvolené hodnoty</button>
</form>
