<?php

define('HEYSTACK_BASE_PATH', dirname(__DIR__));
define('BASE_PATH', dirname(HEYSTACK_BASE_PATH));

use Heystack\Subsystem\Core\Console\Command\GenerateContainer;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Support autoloading when running as installed package
 */
$files = array(
    HEYSTACK_BASE_PATH . '/../vendor/autoload.php'
);

/**
 * Loop through the potential autoload locations
 */
foreach ($files as $file) {
    if (file_exists($file)) {
        $loader = require_once $file;
        break;
    }
}

/**
 * If the autoloader isn't returning a autoload then error
 */
if (is_null($loader) || !$loader instanceof ClassLoader) {
    echo 'You must first install the vendors using composer.' . PHP_EOL;
    exit(1);
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
