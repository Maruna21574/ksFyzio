<?php
meta(['title' => 'Cookies', 'description' => 'Informácie o súboroch cookies a podobných technológiách na webe K&S Fyzio.']);
$headTitle = 'Cookies';
include dirname(__DIR__) . '/partials/page-head.php';
$operator = cfg('company_name') ?: cfg('name');
?>
<section class="section section--flush">
  <div class="container container--narrow prose">
    <h2>Čo sú cookies</h2>
    <p>Cookies sú malé textové súbory, ktoré si webová stránka ukladá vo vašom prehliadači. Pomáhajú jej fungovať, zapamätať si nastavenia alebo zabezpečiť funkcie, o ktoré ste požiadali. Podobne fungujú aj iné technológie ukladania údajov v prehliadači (napríklad lokálne úložisko).</p>

    <h2>Aké cookies používame</h2>
    <p>Používame <strong>iba nevyhnutné (technické) cookies</strong>. Tie sú potrebné na bezpečné fungovanie formulárov, preto na ich použitie <strong>nepotrebujeme váš súhlas</strong> – o ich používaní vás však informujeme tu. <strong>Nepoužívame žiadne analytické, marketingové ani reklamné cookies</strong>, nesledujeme vás a nezdieľame údaje o vašom správaní s reklamnými sieťami.</p>

    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Názov</th><th>Účel</th><th>Platnosť</th><th>Poskytovateľ</th></tr>
        </thead>
        <tbody>
          <tr>
            <td><code>ksf</code></td>
            <td>Bezpečnostný token a ochrana objednávkového a kontaktného formulára pred zneužitím (spamom). Ukladá sa až po otvorení stránky s formulárom.</td>
            <td>Do zatvorenia prehliadača</td>
            <td><?= e($operator) ?> (prvá strana)</td>
          </tr>
          <tr>
            <td><code>ksf_cookie_consent</code></td>
            <td>Zapamätá si vašu voľbu (prijať / odmietnuť) v lište cookies, aby sa vám nezobrazovala pri každej návšteve. Ukladá sa v úložisku prehliadača (nie je to cookie a nikam sa neodosiela).</td>
            <td>Kým ho nezmažete v prehliadači</td>
            <td><?= e($operator) ?> (prvá strana)</td>
          </tr>
          <tr>
            <td><code>ksf_admin</code></td>
            <td>Prihlásenie do správy obsahu. Používa ho iba prevádzkovateľ webu, návštevníkom sa nenastavuje.</td>
            <td>Do zatvorenia prehliadača</td>
            <td><?= e($operator) ?> (prvá strana)</td>
          </tr>
        </tbody>
      </table>
    </div>

    <h2>Vaša voľba</h2>
    <p>V lište cookies môžete voliteľné cookies <strong>prijať alebo odmietnuť</strong>. Nevyhnutné cookies sa používajú v oboch prípadoch, pretože bez nich web a formuláre nefungujú. Momentálne žiadne voliteľné cookies nepoužívame, takže voľba nemá vplyv na fungovanie webu – zapamätáme si ju pre prípad, že by sme ich v budúcnosti pridali. Svoju voľbu môžete kedykoľvek zmeniť cez <a href="<?= e(url('/cookies')) ?>" data-cookie-reopen>Nastavenia cookies</a> (nájdete ich aj v pätičke webu).</p>

    <h2>Písma, obrázky a vložený obsah</h2>
    <p>Písma, obrázky aj skripty webu sú uložené priamo na našom serveri. Nevkladáme mapy, videá ani sociálne moduly tretích strán. Odkazy na Facebook, Instagram a WhatsApp sú obyčajné odkazy – cookies týchto služieb sa nastavia až po tom, čo na ne kliknete a otvoríte ich stránky.</p>

    <h2>Ako cookies spravovať alebo zmazať</h2>
    <p>Cookies môžete kedykoľvek zmazať alebo zablokovať v nastaveniach svojho prehliadača. Ak zablokujete nevyhnutné cookies, formuláre na tomto webe nemusia fungovať – v takom prípade nám môžete zavolať na <a href="tel:<?= e(cfg('phone_href')) ?>"><?= e(cfg('phone')) ?></a> alebo napísať na <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>.</p>

    <h2>Zmeny</h2>
    <p>Ak by sme v budúcnosti začali používať ďalšie cookies (napríklad na meranie návštevnosti), pred ich použitím vás o tom budeme informovať a – tam, kde to zákon vyžaduje – požiadame o súhlas.</p>

    <p>Viac o tom, ako nakladáme s osobnými údajmi, nájdete v <a href="<?= e(url('/ochrana-osobnych-udajov')) ?>">zásadách ochrany osobných údajov</a>.</p>
    <p class="updated">Posledná aktualizácia: <?= e(format_date_sk('2026-10-23')) ?></p>
  </div>
</section>
