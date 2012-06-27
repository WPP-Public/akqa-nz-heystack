<?php

require_once ECOMMERCE_BASE_PATH . '/code/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();

$loader->register();

$loader->registerNamespaces(array(
    'Symfony' => ECOMMERCE_BASE_PATH . '/code'
));

use Symfony\Component\Config\FileLocator;

$locator = new FileLocator(glob(BASE_PATH . '/*', GLOB_ONLYDIR));

if ($subsystems = $locator->locate('_ecommerce_subsystem', null, false)) {
    
    foreach ($subsystems as $dir) {

        $dirname = dirname($dir);

        foreach (glob($dirname . '/code/Ecommerce/Subsystem/*', GLOB_ONLYDIR) as $subsystem) {

            $loader->registerNamespace('Ecommerce\Subsystem\\' . basename($subsystem), $dirname . '/code');

        }

    }
    
    foreach ($subsystems as $dir) {

        $dirname = dirname($dir);

        require_once $dirname . '/config/services.php';

    }

}