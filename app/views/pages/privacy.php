<?php
meta(['title' => 'Ochrana osobných údajov', 'description' => 'Informácie o spracovaní osobných údajov na webe K&S Fyzio: aké údaje spracúvame, prečo, ako dlho a aké máte práva.']);
$headTitle = 'Ochrana osobných údajov';
include dirname(__DIR__) . '/partials/page-head.php';
$operator = cfg('company_name') ?: cfg('name');
?>
<section class="section section--flush">
  <div class="container container--narrow prose">
    <p>Na tejto stránke vysvetľujeme, ako spracúvame vaše osobné údaje podľa Nariadenia (EÚ) 2016/679 (GDPR) a zákona č. 18/2018 Z. z. o ochrane osobných údajov. Snažíme sa o zrozumiteľný jazyk – ak niečomu nerozumiete, napíšte nám.</p>

    <h2>1. Prevádzkovateľ</h2>
    <p><?= e($operator) ?>, <?= e(cfg('city')) ?>, <?= e(cfg('zip')) ?><?php if (cfg('ico')): ?>, IČO: <?= e(cfg('ico')) ?><?php endif; ?><br>
      E-mail: <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>, telefón: <a href="tel:<?= e(cfg('phone_href')) ?>"><?= e(cfg('phone')) ?></a></p>
    <p>Prevádzkovateľ je osoba, ktorá určuje, prečo a ako sa vaše údaje spracúvajú. Zodpovednú osobu nemáme ustanovenú; vo veciach ochrany údajov sa na nás môžete obrátiť na vyššie uvedenom kontakte.</p>

    <h2>2. Aké údaje spracúvame, prečo a na akom základe</h2>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Účel</th><th>Údaje</th><th>Právny základ</th></tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Vybavenie objednávky</strong> cez rezervačný formulár a dohodnutie termínu</td>
            <td>meno a priezvisko, telefón, e-mail, obec/mesto, údaje o zvierati (meno, plemeno, vek, popis ťažkostí), preferovaný termín</td>
            <td>váš súhlas (čl. 6 ods. 1 písm. a GDPR) a opatrenia pred uzavretím zmluvy na vašu žiadosť (písm. b)</td>
          </tr>
          <tr>
            <td><strong>Odpoveď na správu</strong> z kontaktného formulára</td>
            <td>meno a priezvisko, e-mail, telefón (nepovinný), obsah správy</td>
            <td>váš súhlas (čl. 6 ods. 1 písm. a GDPR)</td>
          </tr>
          <tr>
            <td><strong>Poskytnutie služby</strong> a komunikácia počas nej</td>
            <td>údaje uvedené vyššie a údaje, ktoré nám poskytnete pri ošetrení</td>
            <td>plnenie zmluvy (čl. 6 ods. 1 písm. b GDPR)</td>
          </tr>
          <tr>
            <td><strong>Účtovníctvo a plnenie zákonných povinností</strong></td>
            <td>identifikačné a fakturačné údaje</td>
            <td>zákonná povinnosť (čl. 6 ods. 1 písm. c GDPR)</td>
          </tr>
          <tr>
            <td><strong>Ochrana formulárov pred spamom a zneužitím</strong></td>
            <td>IP adresa (ukladáme len jej nevratný odtlačok, nie samotnú adresu), čas odoslania, bezpečnostný token a výsledok automatického overenia, že formulár odosiela človek (vlastné riešenie, bez služieb tretích strán)</td>
            <td>oprávnený záujem na bezpečnosti webu (čl. 6 ods. 1 písm. f GDPR)</td>
          </tr>
        </tbody>
      </table>
    </div>
    <p>Poskytnutie údajov vo formulári je dobrovoľné, bez povinných údajov (meno, e-mail, pri objednávke aj telefón a obec) ho však nevieme vybaviť. Neuskutočňujeme automatizované rozhodovanie ani profilovanie.</p>
    <p>Údaje o vašom zvierati samy osobe sú údajmi o zvierati, nie osobnými údajmi, no v spojení s vaším menom a kontaktom s nimi nakladáme rovnako opatrne.</p>

    <h2>3. Súhlas a jeho odvolanie</h2>
    <p>Pri odoslaní formulára vás žiadame o súhlas začiarknutím políčka. Bez neho formulár odoslať nemožno. Súhlas môžete <strong>kedykoľvek odvolať</strong> e-mailom alebo telefonicky – odvolanie nemá vplyv na zákonnosť spracúvania pred jeho odvolaním. Po odvolaní súhlasu údaje vymažeme, ak ich nepotrebujeme na iný zákonný účel (napríklad účtovníctvo).</p>

    <h2>4. Ako dlho údaje uchovávame</h2>
    <ul>
      <li><strong>Nezáväzné správy a objednávky, ktoré neviedli k spolupráci:</strong> najviac 12 mesiacov od posledného kontaktu.</li>
      <li><strong>Údaje klientov, ktorým poskytujeme službu:</strong> po dobu spolupráce a následne spravidla 3 roky (premlčacia doba nárokov z poskytnutej služby).</li>
      <li><strong>Účtovné doklady:</strong> 10 rokov, ako ustanovuje zákon o účtovníctve.</li>
      <li><strong>Bezpečnostné záznamy formulárov</strong> (limity odoslaní): najviac 24 hodín.</li>
    </ul>
    <p>Po uplynutí lehoty údaje bezpečne vymažeme alebo anonymizujeme.</p>

    <h2>5. Komu údaje poskytujeme</h2>
    <p>Údaje nepredávame a neposkytujeme ich na marketingové účely. Prístup k nim môžu mať len poskytovatelia služieb, ktorých na prevádzku webu a komunikáciu potrebujeme, a to na základe zmluvy o spracúvaní osobných údajov:</p>
    <ul>
      <li><strong>poskytovateľ hostingu</strong> (WebSupport, s. r. o.) – prevádzka webu a odosielanie e-mailov z formulárov,</li>
      <li><strong>poskytovateľ e-mailovej schránky</strong> (Google Ireland Limited – Gmail), do ktorej doručujeme správy z formulárov,</li>
    </ul>
    <p>Poskytovateľ e-mailovej schránky (Google) môže údaje prenášať aj do tretích krajín (USA). Prenos je zabezpečený primeranými zárukami – rozhodnutím Európskej komisie o primeranosti (EU–US Data Privacy Framework) alebo štandardnými zmluvnými doložkami. Údaje môžeme sprístupniť aj orgánom verejnej moci, ak nám to ukladá zákon.</p>

    <h2>6. Cookies</h2>
    <p>Web používa iba technické cookies nevyhnutné na ochranu formulárov pred zneužitím. Nepoužívame analytické ani reklamné cookies. Prehľad nájdete na stránke <a href="<?= e(url('/cookies')) ?>">Cookies</a>.</p>

    <h2>7. Zabezpečenie údajov</h2>
    <p>Web beží cez šifrované pripojenie (HTTPS). Formuláre chránime proti zneužitiu (overenie človeka, obmedzenie počtu odoslaní). Prístup do správy obsahu webu je chránený heslom a vedie ho len prevádzkovateľ. Správy z formulárov sa ukladajú iba v e-mailovej schránke prevádzkovateľa, nie v databáze na webe.</p>

    <h2>8. Vaše práva</h2>
    <p>Voči nám môžete uplatniť tieto práva:</p>
    <ul>
      <li><strong>právo na prístup</strong> – dozviete sa, aké vaše údaje spracúvame, a získate ich kópiu,</li>
      <li><strong>právo na opravu</strong> nesprávnych alebo neúplných údajov,</li>
      <li><strong>právo na vymazanie</strong> („právo byť zabudnutý“), ak na spracúvanie už nie je dôvod,</li>
      <li><strong>právo na obmedzenie spracúvania</strong>,</li>
      <li><strong>právo na prenosnosť</strong> údajov, ktoré sme od vás získali na základe súhlasu alebo zmluvy,</li>
      <li><strong>právo namietať</strong> proti spracúvaniu na základe oprávneného záujmu,</li>
      <li><strong>právo kedykoľvek odvolať súhlas</strong>.</li>
    </ul>
    <p>Žiadosť nám pošlite na <a href="mailto:<?= e(cfg('email')) ?>"><?= e(cfg('email')) ?></a>; odpovieme vám najneskôr do 30 dní. Máte tiež právo podať <strong>sťažnosť Úradu na ochranu osobných údajov SR</strong>, Hraničná 12, 820 07 Bratislava 27 (<a href="https://dataprotection.gov.sk" target="_blank" rel="noopener">dataprotection.gov.sk</a>).</p>

    <h2>9. Zmeny týchto zásad</h2>
    <p>Tieto zásady môžeme upraviť, ak sa zmenia naše služby alebo právne predpisy. Aktuálne znenie je vždy zverejnené na tejto stránke.</p>
    <p class="updated">Posledná aktualizácia: <?= e(format_date_sk('2026-10-23')) ?></p>
  </div>
</section>
