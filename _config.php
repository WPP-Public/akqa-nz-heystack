<?php

\Director::addRules(100, array(
    \CliInputController::$url_segment . '/$Action/$Processor/$ID/$OtherID/$ExtraID' => 'CliInputController'
));

define('HEYSTACK_BASE_PATH', __DIR__);

require_once HEYSTACK_BASE_PATH . '/config/subsystems_load.php';
$container = require_once HEYSTACK_BASE_PATH . '/config/services_load.php';

//if (strpos($_SERVER['REQUEST_URI'], 'dev/build')) {
//
//    ManifestBuilder::create_manifest_file();
//    require(MANIFEST_FILE);
//
//    $container->get('dataobject_generator')->process();
//
//}
