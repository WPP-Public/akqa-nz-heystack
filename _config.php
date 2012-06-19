<?php

require_once __DIR__ . '/code/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();

$loader->registerNamespaces(array(
    'Symfony' => __DIR__ . '/code',
    'Heyday'  => __DIR__ . '/code'
));

$loader->register();

use Heyday\Ecommerce\Config;
use Heyday\Ecommerce\State\State;

$config = new Config;

$state = new State;