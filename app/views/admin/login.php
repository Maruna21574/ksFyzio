<section class="card card--narrow">
  <h1>Prihlásenie</h1>
  <?php if (!$configured): ?>
    <p>Prihlásenie ešte nie je nastavené. Nastavte heslo na serveri podľa návodu.</p>
  <?php else: ?>
  <form method="post" action="<?= e(url('/admin/login')) ?>">
    <input type="hidden" name="_csrf" value="<?= e(adm_csrf()) ?>">
    <label class="fld"><span>Heslo</span><input type="password" name="password" autocomplete="current-password" required autofocus></label>
    <button class="btn btn--primary btn--block">Prihlásiť sa</button>
  </form>
  <?php endif; ?>
</section>
