<?php

use Symfony\Component\ClassLoader\ClassMapGenerator;

//define('UNIT_TESTING', true);
define('HEYSTACK_BASE_PATH', dirname(__DIR__));
define('BASE_PATH', HEYSTACK_BASE_PATH);
define('FRAMEWORK_PATH', HEYSTACK_BASE_PATH . '/framework');

if (!file_exists(HEYSTACK_BASE_PATH . '/vendor/autoload.php')) {
    echo 'You must first install the vendors using composer.' . PHP_EOL;
    exit(1);
}

$loader = require HEYSTACK_BASE_PATH . '/vendor/autoload.php';
$classMap = ClassMapGenerator::createMap(HEYSTACK_BASE_PATH . '/framework');
unset($classMap['PHPUnit_Framework_TestCase']);
$loader->addClassMap($classMap);
$loader->addPsr4('Heystack\\Core\\Test\\', __DIR__.'/Test/');
