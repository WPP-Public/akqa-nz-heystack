<?php

define('HEYSTACK_BASE_PATH', __DIR__);

use Heystack\Subsystem\Core;

require_once 'conf/ConfigureFromEnv.php';

$mode = Director::get_environment_type();
$containerName = "HeystackServiceContainer$mode";
$containerFile = HEYSTACK_BASE_PATH . "/cache/$containerName.php";

if (file_exists($containerFile)) {
    require_once $containerFile;
    Core\ServiceStore::set($container = new $containerName());
    Session::start();
    $container->get('event_dispatcher')->dispatch(Core\Events::READY);
}
