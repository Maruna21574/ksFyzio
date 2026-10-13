<?php
meta(['title' => 'Ochrana osobných údajov', 'description' => 'Informácie o spracovaní osobných údajov na webe K&S Fyzio.']);
$headTitle = 'Ochrana osobných údajov';
include dirname(__DIR__) . '/partials/page-head.php';
$operator = cfg('company_name') ?: cfg('name');
?>
<section class="section section--flush">
  <div class="container container--narrow prose">
    <h2>Prevádzkovateľ</h2>
    <p><?= e($operator) ?>, <?= e(cfg('city')) ?>, <?= e(cfg('zip')) ?><?php if (cfg('ico')): ?>, IČO: <?= e(cfg('ico')) ?><?php endif; ?><br>
      E-mail: <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>, telefón: <a href="tel:<?= e(cfg('phone_href')) ?>"><?= e(cfg('phone')) ?></a></p>

    <h2>Aké údaje spracúvame a prečo</h2>
    <p>Pri odoslaní objednávkového alebo kontaktného formulára spracúvame údaje, ktoré nám sami uvediete: meno, e-mail, telefón, obec, údaje o psovi (meno, plemeno, vek, popis ťažkostí) a obsah správy. Používame ich výhradne na vybavenie vašej objednávky alebo otázky a na komunikáciu s vami.</p>

    <h2>Právny základ a doba uchovávania</h2>
    <p>Údaje spracúvame na základe vášho súhlasu a na účel prípravy a plnenia zmluvy. Uchovávame ich len po dobu nevyhnutnú na vybavenie požiadavky a poskytnutie služby, prípadne po dobu, ktorú vyžaduje zákon.</p>

    <h2>Komu údaje poskytujeme</h2>
    <p>Údaje nepredávame a neposkytujeme tretím stranám na marketingové účely. Správy z formulárov sa doručujú e-mailom prostredníctvom poskytovateľa hostingu.</p>

    <h2>Cookies</h2>
    <p>Web používa iba technické cookies nevyhnutné na ochranu formulárov pred zneužitím (bezpečnostný token). Nepoužívame analytické ani reklamné cookies.</p>

    <h2>Vaše práva</h2>
    <p>Máte právo na prístup k údajom, ich opravu, vymazanie, obmedzenie spracúvania, prenosnosť a právo kedykoľvek odvolať súhlas. Žiadosť nám pošlite na <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>. Máte tiež právo podať sťažnosť Úradu na ochranu osobných údajov SR (<a href="https://dataprotection.gov.sk" target="_blank" rel="noopener">dataprotection.gov.sk</a>).</p>
  </div>
</section>
