<section class="cta-band">
  <div class="container cta-band__inner">
    <div>
      <h2><?= e($ctaTitle ?? 'Pomôžme vášmu psovi hýbať sa bez bolesti') ?></h2>
      <p><?= e($ctaText ?? 'Objednajte sa online alebo nám zavolajte. Radi vám poradíme a dohodneme termín.') ?></p>
    </div>
    <div class="cta-band__actions">
      <a class="btn btn--light" href="<?= e(url('/rezervacia')) ?>"><?= icon('calendar') ?> Objednať sa</a>
      <a class="btn btn--ghost-light" href="tel:<?= e(cfg('phone_href')) ?>"><?= icon('phone') ?> <?= e(cfg('phone')) ?></a>
    </div>
  </div>
</section>
