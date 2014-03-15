<?php

define('HEYSTACK_BASE_PATH', dirname(__DIR__));
define('BASE_PATH', HEYSTACK_BASE_PATH);

require_once BASE_PATH . '/framework/core/Constants.php';
require_once BASE_PATH . '/framework/core/Core.php';

if (!file_exists(HEYSTACK_BASE_PATH . '/vendor/autoload.php')) {
    echo 'You must first install the vendors using composer.' . PHP_EOL;
    exit(1);
}