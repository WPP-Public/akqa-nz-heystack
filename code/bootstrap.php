<?php

define('BASE_PATH', dirname(dirname(__DIR__)));

use Composer\Autoload\ClassLoader;

if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    $loader = require_once BASE_PATH . '/vendor/autoload.php';
}

/**
 * If the autoloader isn't returning a autoload then error
 */
if (is_null($loader) || !$loader instanceof ClassLoader) {
    echo 'You must first install the vendors using composer.' . PHP_EOL;
    exit(1);
}

/**
 * Load silverstripe
 */
$_FILE_TO_URL_MAPPING[BASE_PATH] = 'http://localhost';

require_once BASE_PATH . '/sapphire/core/Core.php';
require_once BASE_PATH . '/sapphire/core/model/DB.php';

global $databaseConfig;
\DB::connect($databaseConfig);

use Heystack\Subsystem\Core\Console\Command\GenerateContainer;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * If the container doesn't exist generate one before requiring it
 */
if (!file_exists(BASE_PATH . '/mysite/code/HeystackServiceContainer.php')) {
    (new GenerateContainer())->run(
        new ArrayInput(array()),
        new NullOutput()
    );
    echo 'Please re-run your command, as the container didn\'t exist and had to be generated', PHP_EOL;
    exit(1);
}
