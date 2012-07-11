<?php

define('HEYSTACK_BASE_PATH', __DIR__);

require_once HEYSTACK_BASE_PATH . '/config/subsystems_load.php';
require_once HEYSTACK_BASE_PATH . '/config/services_load.php';

if (strpos($_SERVER['REQUEST_URI'], 'dev/build')) {

    ManifestBuilder::create_manifest_file();
    require(MANIFEST_FILE);

    Heystack\Subsystem\Core\ServiceStore::getService('dataobjectgenerator')->process();

}
