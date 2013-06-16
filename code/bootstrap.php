<?php

define('BASE_PATH', dirname(dirname(__DIR__)));

use Composer\Autoload\ClassLoader;

/**
 * Ensure heystack is autoloaded
 */
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
 * Start: Load silverstripe
 */
$_FILE_TO_URL_MAPPING[BASE_PATH] = 'http://localhost';

require_once BASE_PATH . '/sapphire/core/Core.php';

if (function_exists('mb_http_output')) {
    mb_http_output('UTF-8');
    mb_internal_encoding('UTF-8');
}

require_once BASE_PATH . '/sapphire/core/model/DB.php';

\ManifestBuilder::create_manifest_file();
/**
 * End: Load silverstripe
 */
