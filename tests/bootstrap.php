<?php

define('UNIT_TESTING', true);
define('HEYSTACK_BASE_PATH', dirname(__DIR__));
define('BASE_PATH', HEYSTACK_BASE_PATH);

if (file_exists(HEYSTACK_BASE_PATH . '/vendor/autoload.php')) {

    $loader = require HEYSTACK_BASE_PATH . '/vendor/autoload.php';

} else {

    $loader = require BASE_PATH . '/vendor/autoload.php';

}

use Symfony\Component\ClassLoader\ClassMapGenerator;

$loader->addClassMap(ClassMapGenerator::createMap(HEYSTACK_BASE_PATH . '/sapphire'));
$loader->add('Heystack\Subsystem\Core\Test', __DIR__);
