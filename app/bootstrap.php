<?php
declare(strict_types=1);

mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Bratislava');

require __DIR__ . '/lib/store.php';

// config.php = predvolené nastavenia; app/storage/config.json ich prekrýva (edituje sa v admine).
$GLOBALS['CONFIG'] = array_replace(require __DIR__ . '/config.php', storage_read('config') ?? []);
$GLOBALS['META']   = [];

require __DIR__ . '/lib/helpers.php';
require __DIR__ . '/lib/forms.php';
require __DIR__ . '/lib/ui.php';

foreach (['services', 'articles', 'faq', 'signals', 'pricing', 'stories', 'offer'] as $d) {
    $GLOBALS[strtoupper($d)] = load_data($d);
}
