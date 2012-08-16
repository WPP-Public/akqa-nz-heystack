<?php

chdir(dirname(dirname(__DIR__)) . '/sapphire');

$_SERVER['SCRIPT_FILENAME'] = getcwd() . '/main.php';

require_once 'core/Core.php';

if (file_exists(HEYSTACK_BASE_PATH . '/vendor/autoload.php')) {

    $loader = require HEYSTACK_BASE_PATH . '/vendor/autoload.php';

} else {

    $loader = require BASE_PATH . '/vendor/autoload.php';

}

$loader->add('Heystack\Subsystem\Core\Test', __DIR__);
