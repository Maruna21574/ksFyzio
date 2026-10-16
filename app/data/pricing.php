<?php
// Cenník. 'groups' = skupiny s riadkami [názov, cena]; 'items' = voliteľné zvýraznené balíčky (karty); 'notes' = poznámky pod cenníkom.
return [
    'groups' => [
        ['title' => 'Fyzioterapia a manuálne techniky', 'note' => '', 'rows' => [
            ['Vstupné vyšetrenie a konzultácia (30 min.)', '25 €'],
            ['Individuálna fyzioterapia (60 min.)', '35 €'],
            ['Mäkké techniky (30 min.)', '20 €'],
            ['Mobilizačné techniky (30 min.)', '20 €'],
            ['Kombinácia mäkkých techník a mobilizácií (60 min.)', '35 €'],
            ['Kineziotaping', '10–15 €'],
        ]],
        ['title' => 'Fyzikálna terapia', 'note' => '', 'rows' => [
            ['Elektroterapia', '10 €'],
            ['Magnetoterapia', '10 €'],
            ['Laseroterapia', '15 €'],
            ['Rázová vlna', '25 €'],
            ['Kombinácia fyzikálnych procedúr', '20 €'],
        ]],
        ['title' => 'Kombinované terapie', 'note' => '', 'rows' => [
            ['Fyzioterapia + elektroterapia', '35 €'],
            ['Fyzioterapia + magnetoterapia', '35 €'],
            ['Fyzioterapia + laseroterapia', '35 €'],
            ['Fyzioterapia + rázová vlna', '40 €'],
        ]],
        ['title' => 'Konzultácie a cvičenie', 'note' => '', 'rows' => [
            ['Konzultácia (15–20 min.)', '15 €'],
            ['Individuálne cvičenie (30 min.)', '20 €'],
            ['Individuálne cvičenie (60 min.)', '35 €'],
            ['Návrh domáceho rehabilitačného plánu', '20 €'],
        ]],
        ['title' => 'Fyzioterapia u klienta doma', 'note' => 'Cena domácej návštevy zahŕňa dopravu v rámci dohodnutej oblasti a vzdialenosti pacienta.', 'rows' => [
            ['Fyzioterapia (30 min.)', '30 €'],
            ['Fyzioterapia (60 min.)', '35 €'],
            ['Komplexná fyzioterapia (60 min.)', '40 €'],
        ]],
    ],
    'items' => [],
    'notes' => [
        'Jednotlivé terapie a ich kombinácie sa prispôsobujú individuálnym potrebám klienta.',
    ],
];
