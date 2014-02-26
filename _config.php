<?php

use Heystack\Core\Events;
use Heystack\Core\Services;
use Heystack\Core\DependencyInjection\SilverStripe\HeystackInjectionCreator;

define('HEYSTACK_BASE_PATH', __DIR__);

/**
 * Heystack requires config from environment
 */
require_once FRAMEWORK_PATH . '/conf/ConfigureFromEnv.php';

/**
 * Ensure things have been configured
 */
global $databaseConfig;

if (empty($databaseConfig['database'])) {
    throw new \RuntimeException(
        'Heystack requires configuration from environment please add an _ss_environment.php ' .
        'file and ensure the environment type and database details are present'
    );
}

/**
 * Session needs to be started before dispatching the ready event
 */
Session::start();

/**
 * Load the autoloader if it exists
 */
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
}

$container = require_once HEYSTACK_BASE_PATH . '/config/container.php';
Injector::inst()->setObjectCreator(new HeystackInjectionCreator($container));

$container->get(Services::EVENT_DISPATCHER)->dispatch(Events::READY);
