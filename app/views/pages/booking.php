<?php
/** @var array $form */
meta([
    'title' => 'Objednať sa – rezervácia fyzioterapie',
    'description' => 'Objednajte svojho psa na fyzioterapiu cez online formulár. Ozveme sa vám a dohodneme termín – aj výjazd k vám domov.',
]);
$headTitle = 'Objednať sa';
$headLead = 'Vyplňte formulár a my sa vám ozveme, aby sme spolu dohodli termín. Môžete sa objednať aj telefonicky alebo cez WhatsApp.';
include dirname(__DIR__) . '/partials/page-head.php';
?>
<section class="section section--flush">
  <div class="container form-layout">
    <div class="form-card">
      <?php if ($form['sent']): ?>
        <div class="form-success" role="status" tabindex="-1" data-success>
          <span class="form-success__icon"><?= icon('check') ?></span>
          <h2>Ďakujeme, objednávku sme prijali!</h2>
          <p>Čoskoro sa vám ozveme a dohodneme termín. Ak to je naliehavé, zavolajte nám na <a href="tel:<?= e(cfg('phone_href')) ?>"><?= e(cfg('phone')) ?></a>.</p>
          <p><a class="btn btn--primary" href="<?= e(url('/')) ?>">Späť na úvod</a></p>
        </div>
      <?php else: ?>
        <form class="form" method="post" action="<?= e(url("/rezervacia")) ?>" novalidate data-ajax-form data-success-title="Ďakujeme, objednávku sme prijali!" data-success-text="Čoskoro sa vám ozveme a dohodneme termín.">
          <?php if ($form['error_global']): ?>
            <p class="form-alert" role="alert"><?= e($form['error_global']) ?></p>
          <?php else: ?>
            <p class="form-alert" role="alert" hidden></p>
          <?php endif; ?>

          <fieldset>
            <legend><span>1</span> Kontaktné údaje</legend>
            <div class="form-grid">
              <?= field('name', 'Meno a priezvisko', $form, ['required' => true, 'autocomplete' => 'name', 'maxlength' => 100]) ?>
              <?= field('phone', 'Telefón', $form, ['type' => 'tel', 'required' => true, 'autocomplete' => 'tel', 'inputmode' => 'tel', 'placeholder' => '0900 000 000']) ?>
              <?= field('email', 'E-mail', $form, ['type' => 'email', 'required' => true, 'autocomplete' => 'email']) ?>
              <?= field('location', 'Obec / mesto', $form, ['required' => true, 'autocomplete' => 'address-level2', 'hint' => 'Kde sa pes nachádza (kvôli výjazdu).']) ?>
            </div>
          </fieldset>

          <fieldset>
            <legend><span>2</span> O vašom psovi</legend>
            <div class="form-grid">
              <?= field('dog_name', 'Meno psa', $form, ['required' => true, 'maxlength' => 60]) ?>
              <?= field('breed', 'Plemeno', $form, ['maxlength' => 80]) ?>
              <?= field('age', 'Vek', $form, ['placeholder' => 'napr. 9 rokov', 'maxlength' => 40]) ?>
              <?= field('service', 'Služba', $form, ['type' => 'select', 'options' => form_service_options()]) ?>
            </div>
            <?= field('message', 'Čo psa trápi?', $form, ['type' => 'textarea', 'rows' => 5, 'maxlength' => 2000, 'placeholder' => 'Napíšte, čo ste si všimli – od kedy, pri akej činnosti, či bol pes u veterinára…']) ?>
          </fieldset>

          <fieldset>
            <legend><span>3</span> Preferovaný termín</legend>
            <div class="form-grid">
              <?= field('date', 'Dátum', $form, ['type' => 'date', 'min' => date('Y-m-d'), 'hint' => 'Nezáväzne – termín potvrdíme.']) ?>
              <?= field('time', 'Časť dňa', $form, ['type' => 'select', 'options' => array_combine(time_slots(), time_slots())]) ?>
            </div>
          </fieldset>

          <div class="hp" aria-hidden="true">
            <label>Nevypĺňať <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
          </div>
          <input type="hidden" name="csrf" value="<?= e($form['csrf']) ?>">

          <div class="field field--check<?= isset($form['errors']['consent']) ? ' has-error' : '' ?>">
            <label class="check">
              <input type="checkbox" name="consent" value="1" id="f-consent" required<?= !empty($form['values']['consent']) ? ' checked' : '' ?>>
              <span>Súhlasím so spracovaním osobných údajov na účel vybavenia objednávky. <a href="<?= e(url('/ochrana-osobnych-udajov')) ?>" target="_blank">Viac informácií</a></span>
            </label>
            <p class="field__error" id="f-consent-err" role="alert"><?= e($form['errors']['consent'] ?? '') ?></p>
          </div>

          <button class="btn btn--primary btn--lg btn--block" type="submit" data-submit>
            <span class="btn__label">Odoslať objednávku</span>
            <span class="btn__loading" aria-hidden="true">Odosielam…</span>
          </button>
          <p class="form-note">Polia označené <span class="req">*</span> sú povinné.</p>
        </form>
      <?php endif; ?>
    </div>

    <aside class="aside">
      <div class="aside__card">
        <h2>Radšej priamo?</h2>
        <ul class="contact-list">
          <li><?= icon('phone') ?><a href="tel:<?= e(cfg('phone_href')) ?>"><?= e(cfg('phone')) ?></a></li>
          <li><?= icon('chat') ?><a href="<?= e(cfg('whatsapp')) ?>" target="_blank" rel="noopener">WhatsApp</a></li>
          <li><?= icon('mail') ?><a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a></li>
        </ul>
      </div>
      <div class="aside__card aside__card--soft">
        <h2>Výjazd k vám domov</h2>
        <p>Chodíme po celom Trnavskom a Bratislavskom kraji. Pes sa tak nemusí stresovať cestovaním.</p>
      </div>
    </aside>
  </div>
</section>
