<?php
declare(strict_types=1);

mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Bratislava');

$GLOBALS['CONFIG'] = require __DIR__ . '/config.php';
$GLOBALS['META']   = [];

require __DIR__ . '/lib/helpers.php';
require __DIR__ . '/lib/forms.php';
require __DIR__ . '/lib/ui.php';

foreach (['services', 'articles', 'faq', 'signals', 'pricing', 'stories', 'offer'] as $d) {
    $GLOBALS[strtoupper($d)] = require __DIR__ . "/data/$d.php";
}
