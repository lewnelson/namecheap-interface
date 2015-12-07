<?php

$vendor_autoload = preg_replace('/Examples$/', 'vendor/autoload.php', __DIR__);
require_once($vendor_autoload);

$example_classes = array_diff(scandir(__DIR__.'/Classes'), array('.', '..'));
foreach($example_classes as $file) {
    require_once(__DIR__.'/Classes/'.$file);
}

?>