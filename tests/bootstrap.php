<?php

define('HEYSTACK_BASE_PATH', dirname(__DIR__));
define('BASE_PATH', dirname(HEYSTACK_BASE_PATH));

if (file_exists(HEYSTACK_BASE_PATH . '/vendor/autoload.php')) {
    $loader = require_once HEYSTACK_BASE_PATH . '/vendor/autoload.php';
} else {
    $loader = require_once BASE_PATH . '/vendor/autoload.php';
}

$loader->add('Heystack\Subsystem\Core\Test', __DIR__);
