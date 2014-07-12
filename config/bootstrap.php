<?php

require_once __DIR__ . '/../../framework/core/Constants.php';
require_once __DIR__ . '/../../framework/core/Core.php';
require_once __DIR__ . '/../../framework/model/DB.php';

global $databaseConfig;
DB::connect($databaseConfig);
Session::start();

$bootstrap = new \Heystack\Core\Bootstrap();
$bootstrap->doBootstrap(new Session($_SESSION));