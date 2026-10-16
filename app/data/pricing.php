<?php
// Cenník – UKÁŽKOVÉ ÚDAJE. Skutočné ceny nahraďte tu a v config.php vypnite 'demo_content'.
// 'price' prázdne = zobrazí sa „Cena na vyžiadanie“.
return [
    'items' => [
        [
            'title'    => 'Vstupné vyšetrenie',
            'price'    => '35 €',
            'unit'     => 'jednorazovo',
            'text'     => 'Rozhovor, vyšetrenie pohybu a návrh ďalšieho postupu.',
            'includes' => ['Rozhovor a anamnéza', 'Vyšetrenie pohybu, svalstva a kĺbov', 'Návrh plánu terapie', 'Rady pre domáce prostredie'],
            'featured' => false,
        ],
        [
            'title'    => 'Fyzioterapia – jedna terapia',
            'price'    => '30 €',
            'unit'     => 'za terapiu',
            'text'     => 'Jednotlivá terapia prispôsobená aktuálnemu stavu zvieraťa.',
            'includes' => ['Manuálne techniky, cvičenia a prístrojová terapia podľa potreby', 'Zhodnotenie pokroku', 'Domáce cvičenia pre majiteľa'],
            'featured' => false,
        ],
        [
            'title'    => 'Balíček 5 terapií',
            'price'    => '135 €',
            'unit'     => 'za 5 terapií',
            'text'     => 'Pre zvieratá po úraze alebo operácii a pri dlhodobejšej podpore pohybu.',
            'includes' => ['5 terapií so zvýhodnenou cenou', 'Priebežné hodnotenie a úprava plánu', 'Podpora medzi stretnutiami'],
            'featured' => true,
        ],
        [
            'title'    => 'Výjazd k vám domov',
            'price'    => 'od 10 €',
            'unit'     => 'príplatok za výjazd',
            'text'     => 'Prídeme za vami v Trnavskom a Bratislavskom kraji. Výška príplatku závisí od vzdialenosti.',
            'includes' => ['Terapia v prostredí, kde sa zviera cíti bezpečne', 'Bez stresu z cestovania', 'Cenu výjazdu vám poviem vopred'],
            'featured' => false,
        ],
    ],
    'notes' => [
        'Ceny sú orientačné a môžu sa líšiť podľa rozsahu terapie a vzdialenosti výjazdu.',
        'Presnú cenu vám vždy poviem pri objednaní, ešte pred návštevou.',
        'Platba je možná na mieste po terapii.',
    ],
];
