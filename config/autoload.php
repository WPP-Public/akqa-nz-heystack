<?php

require_once HEYDAY_ECOMMERCE_BASE_PATH . '/code/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();

$loader->registerNamespaces(array(
    'Symfony' => HEYDAY_ECOMMERCE_BASE_PATH . '/code',
    'Heyday'  => HEYDAY_ECOMMERCE_BASE_PATH . '/code'
));

$loader->register();