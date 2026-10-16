<?php
declare(strict_types=1);

/*
 * Schémy admin editorov. Pole: ['k' => kľúč, 't' => typ, 'l' => popis, ...voľby]
 * Typy: text, textarea, lines, number, checkbox, select, image, slug, blocks, repeater, group.
 */

function adm_f(string $k, string $t, string $l, array $o = []): array
{
    return ['k' => $k, 't' => $t, 'l' => $l] + $o;
}

function adm_icons(): array
{
    return ['activity' => 'Pulz', 'waves' => 'Vlny', 'sun' => 'Slnko', 'search' => 'Lupa', 'shield' => 'Štít', 'heart' => 'Srdce', 'home' => 'Dom', 'paw' => 'Labka', 'chat' => 'Bublina', 'book' => 'Kniha', 'tag' => 'Štítok'];
}

function adm_colors(): array
{
    return ['cyan' => 'Modrá', 'green' => 'Zelená', 'lime' => 'Limetková', 'red' => 'Červená', 'navy' => 'Tmavomodrá'];
}

/** Zoznam editorov: kľúč => [názov, popis, ikona-emoji, ukladací kľúč, wrap] */
function adm_pages(): array
{
    return [
        'texty'     => ['Texty stránok', 'Nadpisy, úvody a odseky na všetkých stránkach.', '✏️'],
        'sluzby'    => ['Služby', 'Karty služieb a ich podstránky. Pridať, zmeniť poradie, odstrániť.', '🩺', 'data-services', 'items'],
        'ponuka'    => ['Pacienti, prístroje, techniky', 'Komu pomáhame, prístroje (laser, magnetoterapia…) a techniky.', '🐾', 'data-offer', null],
        'pribehy'   => ['Príbehy pacientov', 'Príbehy s fotkami pred a po.', '❤️', 'data-stories', 'items'],
        'cennik'    => ['Cenník', 'Položky cenníka a poznámky pod nimi.', '💶', 'data-pricing', null],
        'faq'       => ['Časté otázky', 'Otázky a odpovede na stránke „Ako to funguje“.', '❓', 'data-faq', 'items'],
        'clanky'    => ['Články (Tipy a rady)', 'Články s fotkou a textom.', '📰', 'data-articles', 'items'],
        'signaly'   => ['Test pohybu – signály', 'Zoznam signálov v teste pohybu psa.', '✅', 'data-signals', 'items'],
        'fotky'     => ['Fotky webu', 'Hlavná fotka, maskot a fotka na stránke O nás.', '🖼️', 'images', null],
        'nastavenia' => ['Kontakty a nastavenia', 'Telefón, e-mail, sociálne siete, sekcia o vás, ukážkový obsah.', '⚙️', 'config', null],
    ];
}

function adm_schema(string $page, ?string $group = null): array
{
    $icons = adm_icons();
    $colors = adm_colors();

    switch ($page) {
        case 'sluzby':
            return [adm_f('items', 'repeater', 'Služby', ['label' => 'title', 'add' => 'Pridať službu', 'fields' => [
                adm_f('title', 'text', 'Názov služby', ['req' => true, 'max' => 120]),
                adm_f('slug', 'slug', 'Adresa stránky', ['src' => 'title', 'hint' => 'Nechajte prázdne, vytvorí sa z názvu. Pozor: zmena adresy zruší staré odkazy.']),
                adm_f('short', 'textarea', 'Krátky popis (na karte)', ['rows' => 2, 'max' => 300]),
                adm_f('icon', 'select', 'Ikona', ['options' => $icons]),
                adm_f('color', 'select', 'Farba karty', ['options' => $colors]),
                adm_f('lead', 'textarea', 'Úvod na podstránke', ['rows' => 3]),
                adm_f('when', 'lines', 'Kedy to pomáha (jedna položka na riadok)'),
                adm_f('how', 'repeater', 'Ako to prebieha (kroky)', ['tuple' => true, 'add' => 'Pridať krok', 'fields' => [
                    adm_f('t', 'text', 'Nadpis kroku', ['max' => 120]),
                    adm_f('d', 'textarea', 'Popis kroku', ['rows' => 2]),
                ]]),
                adm_f('note', 'textarea', 'Poznámka na konci', ['rows' => 2]),
            ]])];

        case 'ponuka':
            $card = fn (string $add) => ['tuple' => true, 'add' => $add, 'fields' => [
                adm_f('t', 'text', 'Názov', ['req' => true, 'max' => 120]),
                adm_f('d', 'textarea', 'Popis', ['rows' => 2]),
                adm_f('i', 'select', 'Ikona', ['options' => $icons]),
                adm_f('c', 'select', 'Farba', ['options' => $colors]),
            ]];
            return [
                adm_f('patients', 'repeater', 'Komu pomáhame (skupiny pacientov)', $card('Pridať skupinu')),
                adm_f('devices', 'repeater', 'Prístroje a metódy', $card('Pridať prístroj')),
                adm_f('techniques', 'lines', 'Techniky a metódy (jedna na riadok)'),
            ];

        case 'pribehy':
            return [adm_f('items', 'repeater', 'Príbehy', ['label' => 'name', 'add' => 'Pridať príbeh', 'fields' => [
                adm_f('name', 'text', 'Meno zvieraťa', ['req' => true, 'max' => 60]),
                adm_f('type', 'text', 'Skupina (napr. Pooperačný pacient)', ['max' => 80]),
                adm_f('meta', 'text', 'Druh/plemeno a vek (napr. Kríženec, 12 rokov)', ['max' => 100]),
                adm_f('problem', 'textarea', 'Problém', ['rows' => 2]),
                adm_f('therapy', 'textarea', 'Čo sme robili', ['rows' => 2]),
                adm_f('result', 'textarea', 'Výsledok', ['rows' => 2]),
                adm_f('quote', 'text', 'Citát majiteľa', ['max' => 200]),
                adm_f('weeks', 'number', 'Dĺžka spolupráce (týždne)', ['min' => 1, 'max' => 300, 'def' => 4]),
                adm_f('before', 'image', 'Fotka PRED terapiou', ['prefix' => 'pribeh-pred', 'maxw' => 1200]),
                adm_f('after', 'image', 'Fotka PO terapii', ['prefix' => 'pribeh-po', 'maxw' => 1200]),
                adm_f('alt', 'text', 'Popis fotky (pre nevidiacich)', ['max' => 160]),
            ]])];

        case 'cennik':
            return [
                adm_f('groups', 'repeater', 'Skupiny cenníka (riadky s cenami)', ['label' => 'title', 'add' => 'Pridať skupinu', 'fields' => [
                    adm_f('title', 'text', 'Názov skupiny', ['req' => true, 'max' => 120]),
                    adm_f('rows', 'repeater', 'Riadky cenníka', ['tuple' => true, 'add' => 'Pridať riadok', 'fields' => [
                        adm_f('n', 'text', 'Názov služby (aj s časom, napr. Fyzioterapia (60 min.))', ['max' => 160]),
                        adm_f('p', 'text', 'Cena (napr. 35 € alebo 10–15 €)', ['max' => 30]),
                    ]]),
                    adm_f('note', 'textarea', 'Poznámka pod skupinou (nepovinná)', ['rows' => 2, 'max' => 400]),
                ]]),
                adm_f('items', 'repeater', 'Zvýraznené balíčky (nepovinné karty navyše)', ['label' => 'title', 'add' => 'Pridať balíček', 'fields' => [
                    adm_f('title', 'text', 'Názov', ['req' => true, 'max' => 120]),
                    adm_f('price', 'text', 'Cena (napr. 35 €)', ['max' => 30, 'hint' => 'Prázdne = zobrazí sa „Cena na vyžiadanie“.']),
                    adm_f('unit', 'text', 'Jednotka (napr. za terapiu)', ['max' => 60]),
                    adm_f('text', 'textarea', 'Krátky popis', ['rows' => 2]),
                    adm_f('includes', 'lines', 'Čo obsahuje (jedna položka na riadok)'),
                    adm_f('featured', 'checkbox', 'Zvýrazniť ako „Najvýhodnejšie“'),
                ]]),
                adm_f('notes', 'lines', 'Poznámky pod cenníkom (jedna na riadok)'),
            ];

        case 'faq':
            return [adm_f('items', 'repeater', 'Otázky a odpovede', ['tuple' => true, 'add' => 'Pridať otázku', 'fields' => [
                adm_f('q', 'text', 'Otázka', ['req' => true, 'max' => 200]),
                adm_f('a', 'textarea', 'Odpoveď', ['rows' => 3]),
            ]])];

        case 'clanky':
            return [adm_f('items', 'repeater', 'Články', ['label' => 'title', 'add' => 'Pridať článok', 'fields' => [
                adm_f('title', 'text', 'Nadpis', ['req' => true, 'max' => 160]),
                adm_f('slug', 'slug', 'Adresa článku', ['src' => 'title', 'hint' => 'Nechajte prázdne, vytvorí sa z nadpisu.']),
                adm_f('excerpt', 'textarea', 'Krátky úvod (zobrazí sa v zozname)', ['rows' => 2, 'max' => 300]),
                adm_f('image', 'image', 'Fotka (nepovinná)', ['prefix' => 'clanok', 'maxw' => 1200]),
                adm_f('image_alt', 'text', 'Popis fotky (pre nevidiacich)', ['max' => 160]),
                adm_f('minutes', 'number', 'Čas čítania (minúty)', ['min' => 1, 'max' => 60, 'def' => 3]),
                adm_f('body', 'blocks', 'Text článku', ['hint' => "Odseky oddeľte prázdnym riadkom. Nadpis: riadok začínajúci „## “. Zoznam: riadky začínajúce „- “. Citát: riadok začínajúci „> “."]),
            ]])];

        case 'signaly':
            return [adm_f('items', 'lines', 'Signály (jeden na riadok)', ['rows' => 10, 'hint' => 'Poradie sa používa v teste pohybu, preto nové signály pridávajte radšej na koniec.'])];

        case 'fotky':
            return [
                adm_f('hero', 'image', 'Hlavná fotka na úvodnej stránke', ['prefix' => 'hero', 'maxw' => 1000, 'small' => true, 'default' => site_img_defaults()['hero'], 'hint' => 'Najlepšie na výšku.']),
                adm_f('mascot', 'image', 'Maskot (úvodná stránka)', ['prefix' => 'maskot', 'maxw' => 900, 'default' => site_img_defaults()['mascot']]),
                adm_f('about', 'image', 'Fotka na stránke O nás', ['prefix' => 'o-nas', 'maxw' => 1000, 'default' => site_img_defaults()['about']]),
            ];

        case 'nastavenia':
            return [
                adm_f('phone', 'text', 'Telefón (napr. 0902 082 286)', ['req' => true, 'max' => 30]),
                adm_f('email', 'text', 'E-mail na webe', ['req' => true, 'max' => 120]),
                adm_f('mail_to', 'text', 'E-mail, kam chodia objednávky a správy z formulárov', ['req' => true, 'max' => 120]),
                adm_f('whatsapp', 'text', 'Odkaz na WhatsApp', ['max' => 200, 'hint' => 'Tvar: https://wa.me/421902082286 (číslo bez medzier a bez +).']),
                adm_f('facebook', 'text', 'Facebook (odkaz)', ['max' => 300, 'hint' => 'Prázdne = ikona sa nezobrazí.']),
                adm_f('instagram', 'text', 'Instagram (odkaz)', ['max' => 300]),
                adm_f('city', 'text', 'Mesto', ['max' => 80]),
                adm_f('zip', 'text', 'PSČ', ['max' => 12]),
                adm_f('area', 'lines', 'Oblasť pôsobenia (jedna na riadok)'),
                adm_f('demo_pricing', 'checkbox', 'Cenník je ukážkový (zobrazí upozornenie a skryje ho pred Googlom)'),
                adm_f('demo_content', 'checkbox', 'Príbehy sú ukážkové (zobrazí upozornenie a skryje ich pred Googlom; vypnite, keď sú tam skutočné príbehy)'),
                adm_f('therapist', 'group', 'O vás (stránka O nás)', ['fields' => [
                    adm_f('name', 'text', 'Meno', ['max' => 80]),
                    adm_f('role', 'text', 'Povolanie', ['max' => 100]),
                    adm_f('photo', 'image', 'Vaša fotka', ['prefix' => 'terapeutka', 'maxw' => 1000]),
                    adm_f('bio', 'lines', 'Text o vás (jeden odsek na riadok)', ['rows' => 8, 'hint' => 'Sekcia sa na webe zobrazí, až keď je tu vyplnený text.']),
                ]]),
            ];

        case 'texty':
            $out = [];
            foreach ((text_groups()[$group]['fields'] ?? []) as $k => [$label, $type]) {
                $out[] = $type === 'line'
                    ? adm_f($k, 'text', $label, ['max' => 400])
                    : adm_f($k, 'textarea', $label, ['rows' => $type === 'lines' ? 5 : 3, 'max' => 3000, 'hint' => $type === 'lines' ? 'Jedna položka na riadok.' : '']);
            }
            return $out;
    }
    return [];
}
