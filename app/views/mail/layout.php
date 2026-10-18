<?php
/** @var string $title */
/** @var string $pre */
/** @var string $inner */
$font = "'Segoe UI',Roboto,Helvetica,Arial,sans-serif";
?>
<!doctype html>
<html lang="sk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light">
<title><?= e($title) ?></title>
</head>
<body style="margin:0;padding:0;background:#f1f9fe;font-family:<?= $font ?>;color:#1d2838;">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;"><?= e($pre) ?></div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f1f9fe;">
  <tr>
    <td align="center" style="padding:24px 12px;">
      <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #d9e6ef;">
        <tr>
          <td align="center" style="padding:24px 24px 16px;">
            <a href="<?= e(absolute_url('/')) ?>" style="text-decoration:none;">
              <img src="<?= e(absolute_url('assets/img/logo.png')) ?>" width="120" alt="<?= e(cfg('name')) ?>" style="display:block;width:120px;height:auto;border:0;">
            </a>
          </td>
        </tr>
        <tr>
          <td style="height:5px;line-height:5px;font-size:0;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
              <td width="34%" style="height:5px;background:#13b5d8;font-size:0;line-height:0;">&nbsp;</td>
              <td width="33%" style="height:5px;background:#22a447;font-size:0;line-height:0;">&nbsp;</td>
              <td width="33%" style="height:5px;background:#d9001b;font-size:0;line-height:0;">&nbsp;</td>
            </tr></table>
          </td>
        </tr>
        <tr>
          <td style="padding:28px 28px 8px;font-size:16px;line-height:1.55;color:#1d2838;">
            <?= $inner ?>
          </td>
        </tr>
        <tr>
          <td style="padding:16px 28px 28px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #d9e6ef;">
              <tr>
                <td style="padding-top:16px;font-size:13px;line-height:1.6;color:#566275;text-align:center;">
                  <strong style="color:#0a3a7a;"><?= e(cfg('name')) ?></strong> – <?= e(cfg('tagline')) ?><br>
                  <a href="tel:<?= e(cfg('phone_href')) ?>" style="color:#0b6fa8;text-decoration:none;"><?= e(cfg('phone')) ?></a>
                  &nbsp;·&nbsp;
                  <a href="mailto:<?= e(cfg('email')) ?>" style="color:#0b6fa8;text-decoration:none;"><?= e(cfg('email')) ?></a><br>
                  <?= e(cfg('city')) ?> a okolie ·
                  <a href="<?= e(absolute_url('/')) ?>" style="color:#0b6fa8;text-decoration:none;"><?= e(preg_replace('~^https?://~', '', rtrim(absolute_url('/'), '/'))) ?></a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
