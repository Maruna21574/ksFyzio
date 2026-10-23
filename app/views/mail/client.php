<?php
/** @var string $which  'booking' | 'contact' */
/** @var array $v */
/** @var array $rows  zhrnutie bez mena a e-mailu */
$booking = $which === 'booking';
?>
<h1 style="margin:0 0 14px;font-size:24px;line-height:1.25;color:#0a3a7a;">
  <?= $booking ? 'Ďakujeme za objednávku!' : 'Ďakujeme za vašu správu!' ?>
</h1>
<p style="margin:0 0 12px;">Dobrý deň, <strong><?= e($v['name']) ?></strong>,</p>
<p style="margin:0 0 20px;">
  <?php if ($booking): ?>
    vašu objednávku sme prijali. <strong>Čoskoro sa vám ozveme</strong> a dohodneme termín, ktorý vám bude vyhovovať.
  <?php else: ?>
    vašu správu sme prijali. <strong>Čoskoro sa vám ozveme</strong> s odpoveďou.
  <?php endif; ?>
</p>

<?php if ($rows): ?>
<p style="margin:0 0 8px;font-size:14px;font-weight:700;color:#0a3a7a;">Zhrnutie objednávky</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #d9e6ef;border-radius:12px;border-collapse:separate;overflow:hidden;">
  <?php $i = 0; foreach ($rows as $label => $value): ?>
    <tr>
      <td width="34%" valign="top" style="padding:9px 14px;font-size:14px;color:#566275;background:<?= $i % 2 ? '#ffffff' : '#f1f9fe' ?>;"><?= e($label) ?></td>
      <td valign="top" style="padding:9px 14px;font-size:15px;font-weight:600;color:#1d2838;background:<?= $i % 2 ? '#ffffff' : '#f1f9fe' ?>;"><?= e($value) ?></td>
    </tr>
  <?php $i++; endforeach; ?>
</table>
<?php endif; ?>

<p style="margin:24px 0 8px;">
  Ak je to naliehavé, <strong>zavolajte nám</strong> na
  <a href="tel:<?= e(cfg('phone_href')) ?>" style="color:#0b6fa8;font-weight:700;text-decoration:none;"><?= e(cfg('phone')) ?></a>
  alebo odpovedzte na tento e-mail.
</p>
<p style="margin:0 0 22px;">Tešíme sa na vás a vášho štvornohého kamaráta.</p>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 20px;">
  <tr>
    <td style="border-radius:999px;background:#0b6fa8;">
      <a href="<?= e(absolute_url('/')) ?>" style="display:inline-block;padding:12px 24px;border-radius:999px;font-weight:700;font-size:15px;color:#ffffff;text-decoration:none;">Navštíviť web</a>
    </td>
  </tr>
</table>

<p style="margin:0 0 4px;font-size:12px;line-height:1.5;color:#566275;">
  Tento e-mail ste dostali, lebo bol z vašej adresy odoslaný formulár na našom webe. Ak ste ho neodoslali vy, môžete ho ignorovať.
</p>
