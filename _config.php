<?php

\Director::addRules(100, array(
    \CliInputController::$url_segment . '/$Action/$Processor/$ID/$OtherID/$ExtraID' => 'CliInputController'
));

define('HEYSTACK_BASE_PATH', __DIR__);

$file = HEYSTACK_BASE_PATH . '/cache/HeystackServiceContainer.php';

if (file_exists($file)) {

    require_once $file;
    $container = new HeystackServiceContainer();

} else {
    echo 'Heystack requires a container to run. Please run \'heystack generate-container\'', PHP_EOL;
    exit(1);
}

Heystack\Subsystem\Core\ServiceStore::set($container);

return $container;

$container = require_once HEYSTACK_BASE_PATH . '/config/services_load.php';

Session::start();

$container->get('event_dispatcher')->dispatch(\Heystack\Subsystem\Core\Events::READY);
