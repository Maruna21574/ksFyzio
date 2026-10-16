<section class="card card--narrow">
  <h1>Zmena hesla</h1>
  <form method="post" action="<?= e(url('/admin/heslo')) ?>">
    <input type="hidden" name="_csrf" value="<?= e(adm_csrf()) ?>">
    <label class="fld"><span>Súčasné heslo</span><input type="password" name="current" autocomplete="current-password" required></label>
    <label class="fld"><span>Nové heslo (aspoň 10 znakov)</span><input type="password" name="new" autocomplete="new-password" minlength="10" required></label>
    <label class="fld"><span>Nové heslo znova</span><input type="password" name="new2" autocomplete="new-password" minlength="10" required></label>
    <button class="btn btn--primary">Zmeniť heslo</button>
    <a class="btn" href="<?= e(url('/admin')) ?>">Späť</a>
  </form>
</section>
