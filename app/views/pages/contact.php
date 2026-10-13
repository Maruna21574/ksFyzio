<?php
/** @var array $form */
meta([
    'title' => 'Kontakt',
    'description' => 'Kontaktujte K&S Fyzio – telefón 0902 082 286, e-mail, WhatsApp. Sereď, výjazdy v Trnavskom a Bratislavskom kraji.',
]);
$headTitle = 'Kontakt';
$headLead = 'Máte otázku alebo si nie ste istí, či vášmu psovi fyzioterapia pomôže? Ozvite sa nám.';
include dirname(__DIR__) . '/partials/page-head.php';
?>
<section class="section section--flush">
  <div class="container form-layout">
    <div class="contact-cards">
      <a class="contact-card" href="tel:<?= e(cfg('phone_href')) ?>">
        <span class="contact-card__icon"><?= icon('phone') ?></span>
        <span><small>Zavolajte nám</small><strong><?= e(cfg('phone')) ?></strong></span>
      </a>
      <a class="contact-card" href="mailto:<?= e(cfg('email')) ?>">
        <span class="contact-card__icon contact-card__icon--green"><?= icon('mail') ?></span>
        <span><small>Napíšte e-mail</small><strong><?= e(cfg('email')) ?></strong></span>
      </a>
      <a class="contact-card" href="<?= e(cfg('whatsapp')) ?>" target="_blank" rel="noopener">
        <span class="contact-card__icon contact-card__icon--lime"><?= icon('chat') ?></span>
        <span><small>WhatsApp</small><strong>Napísať správu</strong></span>
      </a>
      <div class="contact-card">
        <span class="contact-card__icon contact-card__icon--red"><?= icon('pin') ?></span>
        <span><small>Sídlo a oblasť pôsobenia</small><strong><?= e(cfg('city')) ?>, <?= e(cfg('zip')) ?></strong><em>Výjazdy: Trnavský a Bratislavský kraj</em></span>
      </div>
      <?php if (cfg('facebook') || cfg('instagram')): ?>
        <div class="socials socials--solid">
          <?php if (cfg('facebook')): ?><a href="<?= e(cfg('facebook')) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a><?php endif; ?>
          <?php if (cfg('instagram')): ?><a href="<?= e(cfg('instagram')) ?>" target="_blank" rel="noopener" aria-label="Instagram"><?= icon('instagram') ?></a><?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="form-card">
      <?php if ($form['sent']): ?>
        <div class="form-success" role="status" tabindex="-1" data-success>
          <span class="form-success__icon"><?= icon('check') ?></span>
          <h2>Správa odoslaná</h2>
          <p>Ďakujeme, ozveme sa vám čo najskôr.</p>
        </div>
      <?php else: ?>
        <h2 class="form-card__title">Napíšte nám</h2>
        <form class="form" method="post" action="<?= e(url("/kontakt")) ?>" novalidate data-ajax-form data-success-title="Správa odoslaná" data-success-text="Ďakujeme, ozveme sa vám čo najskôr.">
          <?php if ($form['error_global']): ?>
            <p class="form-alert" role="alert"><?= e($form['error_global']) ?></p>
          <?php else: ?>
            <p class="form-alert" role="alert" hidden></p>
          <?php endif; ?>

          <div class="form-grid">
            <?= field('name', 'Meno a priezvisko', $form, ['required' => true, 'autocomplete' => 'name']) ?>
            <?= field('email', 'E-mail', $form, ['type' => 'email', 'required' => true, 'autocomplete' => 'email']) ?>
            <?= field('phone', 'Telefón (nepovinné)', $form, ['type' => 'tel', 'autocomplete' => 'tel', 'inputmode' => 'tel']) ?>
          </div>
          <?= field('message', 'Vaša správa', $form, ['type' => 'textarea', 'rows' => 6, 'required' => true, 'maxlength' => 3000]) ?>

          <div class="hp" aria-hidden="true">
            <label>Nevypĺňať <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
          </div>
          <input type="hidden" name="csrf" value="<?= e($form['csrf']) ?>">

          <div class="field field--check<?= isset($form['errors']['consent']) ? ' has-error' : '' ?>">
            <label class="check">
              <input type="checkbox" name="consent" value="1" id="f-consent" required<?= !empty($form['values']['consent']) ? ' checked' : '' ?>>
              <span>Súhlasím so spracovaním osobných údajov na účel vybavenia správy. <a href="<?= e(url('/ochrana-osobnych-udajov')) ?>" target="_blank">Viac informácií</a></span>
            </label>
            <p class="field__error" id="f-consent-err" role="alert"><?= e($form['errors']['consent'] ?? '') ?></p>
          </div>

          <button class="btn btn--primary btn--lg" type="submit" data-submit>
            <span class="btn__label">Odoslať správu</span>
            <span class="btn__loading" aria-hidden="true">Odosielam…</span>
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>
