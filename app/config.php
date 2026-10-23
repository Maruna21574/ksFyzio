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

    // Sekcia „Kto sa o vaše zviera postará“ na stránke O nás. Zobrazí sa, keď je vyplnený text (bio).
    // Fotku nahrajte do assets/img/ a uveďte cestu v 'photo' (napr. assets/img/terapeutka.webp).
    'therapist' => [
        'name'  => '',
        'role'  => 'Veterinárna fyzioterapeutka',
        'photo' => '',
        'bio'   => [], // zoznam odsekov
    ],

    // Voliteľné údaje do ochrany osobných údajov (zobrazia sa, ak sú vyplnené).
    'company_name' => '',
    'ico'          => '',

    // Odosielanie formulárov
    'mail_to'   => 'ks.fyzio@gmail.com', // kam chodia objednávky a správy
    'mail_from' => 'info@ksfyzio.sk',    // odosielateľ mailov (adresa z vašej domény; prázdne = noreply@<doména webu>)
    'subject_prefix' => '[ksfyzio.sk]',
];
