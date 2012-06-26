<?php

define('HEYDAY_ECOMMERCE_BASE_PATH', __DIR__);

require_once HEYDAY_ECOMMERCE_BASE_PATH . '/config/autoload.php';
require_once HEYDAY_ECOMMERCE_BASE_PATH . '/config/services.php';

// use Heyday\Ecommerce\ServiceStore;

// $state = ServiceStore::getService('state');
// $dispatcher = ServiceStore::getService('event_dispatcher');

// \HeydayLog::add_default_file_writer();

// $dispatcher->addListener(\Heyday\Ecommerce\State\Events::STATE_SET, function () {
//     \HeydayLog::log('Something set to state');
// });

// $dispatcher->addListener(\Heyday\Ecommerce\State\Events::STATE_REMOVE, function () {
//     \HeydayLog::log('Something removed to state');
// });

// $state->setByKey('hello', 'something');
// $state->setByKey('hello', 'something');
// $state->setByKey('hello', 'something');
// $state->setByKey('hello', 'something');
// $state->removeByKey('hello');