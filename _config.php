<?php

\Director::addRules(100, array(
    \CliInputController::$url_segment . '/$Action/$Processor/$ID/$OtherID/$ExtraID' => 'CliInputController'
));

define('HEYSTACK_BASE_PATH', __DIR__);

$container = require_once HEYSTACK_BASE_PATH . '/config/services_load.php';

Session::start();

$container->get('event_dispatcher')->dispatch(\Heystack\Subsystem\Core\Events::READY);
