<?php
/** @var string $which  'booking' | 'contact' */
/** @var array $v */
/** @var array $rows */
$booking = $which === 'booking';
$btn = 'display:inline-block;padding:12px 22px;border-radius:999px;font-weight:700;font-size:15px;text-decoration:none;';
?>
<p style="margin:0 0 6px;font-size:13px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#0d9bb8;">
  <?= $booking ? 'Nová objednávka z webu' : 'Nová správa z kontaktného formulára' ?>
</p>
<h1 style="margin:0 0 18px;font-size:24px;line-height:1.25;color:#0a3a7a;">
  <?= e($v['name']) ?>
</h1>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #d9e6ef;border-radius:12px;border-collapse:separate;overflow:hidden;">
  <?php $i = 0; foreach ($rows as $label => $value): ?>
    <tr>
      <td width="34%" valign="top" style="padding:10px 14px;font-size:14px;color:#566275;background:<?= $i % 2 ? '#ffffff' : '#f1f9fe' ?>;"><?= e($label) ?></td>
      <td valign="top" style="padding:10px 14px;font-size:15px;font-weight:600;color:#1d2838;background:<?= $i % 2 ? '#ffffff' : '#f1f9fe' ?>;">
        <?php if ($label === 'E-mail'): ?>
          <a href="mailto:<?= e($value) ?>" style="color:#0b6fa8;text-decoration:none;"><?= e($value) ?></a>
        <?php elseif ($label === 'Telefón' && $value !== '–' && $value !== ''): ?>
          <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $value)) ?>" style="color:#0b6fa8;text-decoration:none;"><?= e($value) ?></a>
        <?php else: ?>
          <?= e($value) ?>
        <?php endif; ?>
      </td>
    </tr>
  <?php $i++; endforeach; ?>
</table>

<p style="margin:22px 0 6px;font-size:14px;font-weight:700;color:#0a3a7a;"><?= $booking ? 'Popis ťažkostí' : 'Správa' ?></p>
<div style="padding:14px 16px;background:#f1f9fe;border-left:4px solid #13b5d8;border-radius:8px;font-size:15px;line-height:1.6;color:#1d2838;">
  <?= $v['message'] !== '' ? nl2br(e($v['message'])) : '–' ?>
</div>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:24px 0 8px;">
  <tr>
    <?php if ($v['phone'] !== ''): ?>
      <td style="padding-right:10px;">
        <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $v['phone'])) ?>" style="<?= $btn ?>background:#0b6fa8;color:#ffffff;">Zavolať</a>
      </td>
    <?php endif; ?>
    <td>
      <a href="mailto:<?= e($v['email']) ?>" style="<?= $btn ?>background:#ffffff;color:#0b6fa8;border:2px solid #0b6fa8;">Odpovedať e-mailom</a>
    </td>
  </tr>
</table>

<p style="margin:16px 0 0;font-size:12px;line-height:1.5;color:#566275;">
  Odoslané <?= e(date('j.n.Y H:i')) ?> · súhlas so spracovaním údajov: áno. Odpoveď na tento e-mail ide priamo klientovi.
</p>
