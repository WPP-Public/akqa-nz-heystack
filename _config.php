<?php

define('HEYSTACK_BASE_PATH', __DIR__);

require_once HEYSTACK_BASE_PATH . '/config/subsystems_load.php';
require_once HEYSTACK_BASE_PATH . '/config/services_load.php';

\Director::addRules(100, array(
    \EcommerceInputController::$url_segment . '//$Action/$ID' => 'EcommerceInputController'
));
