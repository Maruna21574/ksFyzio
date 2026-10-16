<h1>Čo chcete upraviť?</h1>
<p class="lead">Zmeny sa ukladajú hneď a sú ihneď vidieť na webe.</p>
<div class="tiles">
  <?php foreach ($pages as $k => $p): ?>
    <a class="tile" href="<?= e(url('/admin/edit/' . $k)) ?>">
      <span class="tile__ico" aria-hidden="true"><?= $p[2] ?></span>
      <strong><?= e($p[0]) ?></strong>
      <small><?= e($p[1]) ?></small>
    </a>
  <?php endforeach; ?>
</div>
