# K&S Fyzio – web

PHP (bez frameworku) + SCSS + vanilla JS, build cez Node.js.

## Vývoj
    npm install
    npm run build        # CSS, JS, fonty, obrázky -> assets/
    npm run watch:css    # pri úprave SCSS
    npm run watch:js     # pri úprave JS

Lokálne beží cez Laragon (http://ksfyzio.test). Mail sa lokálne ukladá do app/storage/mail-dev.log, ak mail() zlyhá.

## Nasadenie na WebSupport
    npm run dist
Obsah priečinka `dist/` nahrajte cez FTP do `web/` (document root). Nahrávať netreba node_modules, src ani scripts.

## Úpravy obsahu
- Kontakty, e-mail príjemcu, sociálne siete: `app/config.php`
- Služby: `app/data/services.php`, články: `app/data/articles.php`, FAQ: `app/data/faq.php`
- Texty stránok: `app/views/pages/`
