<?php
// Hlavná konfigurácia webu K&S Fyzio. Údaje upravujete tu – na jednom mieste.
return [
    'name'     => 'K&S Fyzio',
    'tagline'  => 'Posúvame hranice pohybu',
    'locale'   => 'sk_SK',

    'phone'      => '0902 082 286',
    'phone_href' => '+421902082286',
    'email'      => 'ks.fyzio@gmail.com',
    'whatsapp'   => 'https://wa.me/421902082286',

    // Sociálne siete – odkazy doplňte, prázdne sa na webe nezobrazia.
    'facebook'  => 'https://www.facebook.com/profile.php?id=61582710817260',
    'instagram' => 'https://www.instagram.com/ks_fyzio/',
    'messenger' => '',

    'city'    => 'Sereď',
    'zip'     => '926 01',
    'region'  => 'Trnavský kraj',
    'area'    => ['Sereď', 'Trnavský kraj', 'Bratislavský kraj'],

    // Voliteľné údaje do ochrany osobných údajov (zobrazia sa, ak sú vyplnené).
    'company_name' => '',
    'ico'          => '',

    // true = stránky Cenník a Príbehy zobrazujú upozornenie „ukážkový obsah“ a nie sú indexované.
    // Po doplnení skutočných údajov nastavte na false.
    'demo_content' => true,

    // Odosielanie formulárov
    'mail_to'   => 'ks.fyzio@gmail.com', // kam chodia objednávky a správy
    'mail_from' => '',                   // prázdne = noreply@<doména webu>; odporúčané nastaviť na adresu z vašej domény
    'subject_prefix' => '[ksfyzio.sk]',
];
