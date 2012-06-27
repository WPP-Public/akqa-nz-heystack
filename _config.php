<?php

define('ECOMMERCE_BASE_PATH', __DIR__);

require_once ECOMMERCE_BASE_PATH . '/config/autoload.php';
require_once ECOMMERCE_BASE_PATH . '/config/services_load.php';

\Director::addRules(100, array(
    \EcommerceInputController::$url_segment . '//$Action/$ID' => 'EcommerceInputController'
));
