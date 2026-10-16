<footer class="site-footer">
  <div class="container site-footer__grid">
    <div class="site-footer__brand">
      <img src="<?= e(asset('assets/img/logo.webp')) ?>" alt="<?= e(cfg('name')) ?>" width="560" height="360" loading="lazy" decoding="async">
      <p><?= e(cfg('tagline')) ?>. <?= e(t('footer_text')) ?></p>
    </div>

    <div>
      <h2 class="site-footer__title">Služby</h2>
      <ul class="site-footer__list">
        <?php foreach ($GLOBALS['SERVICES'] as $s): ?>
          <li><a href="<?= e(url('/sluzby/' . $s['slug'])) ?>"><?= e($s['title']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div>
      <h2 class="site-footer__title">Web</h2>
      <ul class="site-footer__list">
        <li><a href="<?= e(url('/ako-to-funguje')) ?>">Ako to funguje</a></li>
        <li><a href="<?= e(url('/cennik')) ?>">Cenník</a></li>
        <li><a href="<?= e(url('/pribehy')) ?>">Príbehy pacientov</a></li>
        <li><a href="<?= e(url('/o-nas')) ?>">O nás</a></li>
        <li><a href="<?= e(url('/tipy')) ?>">Tipy a rady</a></li>
        <li><a href="<?= e(url('/test-pohybu')) ?>">Test pohybu psa</a></li>
        <li><a href="<?= e(url('/vek-psa')) ?>">Vek psa v ľudských rokoch</a></li>
        <li><a href="<?= e(url('/rezervacia')) ?>">Objednať sa</a></li>
        <li><a href="<?= e(url('/kontakt')) ?>">Kontakt</a></li>
        <li><a href="<?= e(url('/ochrana-osobnych-udajov')) ?>">Ochrana osobných údajov</a></li>
      </ul>
    </div>

    <div>
      <h2 class="site-footer__title">Kontakt</h2>
      <ul class="site-footer__list site-footer__list--icons">
        <li><?= icon('phone') ?><a href="tel:<?= e(cfg('phone_href')) ?>"><?= e(cfg('phone')) ?></a></li>
        <li><?= icon('mail') ?><a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a></li>
        <li><?= icon('pin') ?><span><?= e(cfg('city')) ?>, <?= e(cfg('zip')) ?><br>výjazdy: Trnavský a Bratislavský kraj</span></li>
      </ul>
      <?php if (cfg('facebook') || cfg('instagram')): ?>
      <div class="socials">
        <?php if (cfg('facebook')): ?><a href="<?= e(cfg('facebook')) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a><?php endif; ?>
        <?php if (cfg('instagram')): ?><a href="<?= e(cfg('instagram')) ?>" target="_blank" rel="noopener" aria-label="Instagram"><?= icon('instagram') ?></a><?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="site-footer__bottom">
    <div class="container">
      <span>© <?= date('Y') ?> <?= e(cfg('name')) ?>. Všetky práva vyhradené.</span>
      <span><?= e(t('footer_note')) ?></span>
    </div>
  </div>
</footer>
