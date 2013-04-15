<?php

\Director::addRules(100, array(
    \CliInputController::$url_segment . '/$Action/$Processor/$ID/$OtherID/$ExtraID' => 'CliInputController'
));

define('HEYSTACK_BASE_PATH', __DIR__);

use Heystack\Subsystem\Core;

if (file_exists(BASE_PATH . '/mysite/code/HeystackServiceContainer.php')) {
    require_once 'conf/ConfigureFromEnv.php';
    require_once BASE_PATH . '/mysite/code/HeystackServiceContainer' . Director::get_environment_type() . '.php';
    Core\ServiceStore::set($container = new HeystackServiceContainer());
    Session::start();
    $container->get('event_dispatcher')->dispatch(Core\Events::READY);
}
