<?php

define('HEYSTACK_BASE_PATH', dirname(__DIR__));
define('BASE_PATH', dirname(HEYSTACK_BASE_PATH));

use Heystack\Subsystem\Core\Console\Command\GenerateContainer;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\ClassLoader\ClassMapGenerator;

if (file_exists(HEYSTACK_BASE_PATH . '/../vendor/autoload.php')) {
    $loader = require_once HEYSTACK_BASE_PATH . '/../vendor/autoload.php';
}

/**
 * If the autoloader isn't returning a autoload then error
 */
if (is_null($loader) || !$loader instanceof ClassLoader) {
    echo 'You must first install the vendors using composer.' . PHP_EOL;
    exit(1);
}

$loader->addClassMap(ClassMapGenerator::createMap(BASE_PATH . '/sapphire'));

/**
 * For each subsystem load their config
 */
foreach (glob(BASE_PATH . '/*/_heystack_subsystem') as $file) {
    require_once dirname($file) . '/_config.php';
}


/**
 * If the container doesn't exist generate one before requiring it
 */
if (!file_exists(HEYSTACK_BASE_PATH . '/cache/HeystackServiceContainer.php')) {
    (new GenerateContainer())->run(
        new ArrayInput(array()),
        new NullOutput()
    );
}

/**
 * Require the container
 */
require_once HEYSTACK_BASE_PATH . '/cache/HeystackServiceContainer.php';