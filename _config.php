<?php

//Current changes required to use Symfony components, manifest builder 

require_once __DIR__ . '/code/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();

$loader->registerNamespaces(array(
    'Symfony' => __DIR__ . '/code',
    'Heyday'  => __DIR__ . '/code'
));

$loader->register();

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

use Heyday\Ecommerce\State\State;

$file = __DIR__ . '/cache/container.php';

if (file_exists($file)) {

    require_once $file;
    $container = new ProjectServiceContainer();

} else {

    $loader = new YamlFileLoader($container = new ContainerBuilder(), new FileLocator(array(
        BASE_PATH . '/mysite/config/',
        __DIR__ . '/config/'
    )));

    $loader->load('services.yml');

    $container->compile();

    $dumper = new PhpDumper($container);
    file_put_contents($file, $dumper->dump());

}

Heyday\Ecommerce\ServiceStore::set($container);


Heyday\Ecommerce\ServiceStore::getService('state');