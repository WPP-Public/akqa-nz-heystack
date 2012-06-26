<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass;

use Heyday\Ecommerce\MysiteContainerExtension;
use Heyday\Ecommerce\EcommerceContainerExtension;
use Heyday\Ecommerce\State\State;

$file = HEYDAY_ECOMMERCE_BASE_PATH . '/cache/container.php';

if (file_exists($file) && !isset($_GET['flush'])) {

    require_once $file;
    $container = new EcommerceServiceContainer();

} else {

    $loader = new YamlFileLoader(
        $container = new ContainerBuilder(),
        new FileLocator(array(
            HEYDAY_ECOMMERCE_BASE_PATH . '/config/'
        ))
    );

    $container->registerExtension(new EcommerceContainerExtension());
    $container->registerExtension(new MysiteContainerExtension());

    $loader->load('extensions.yml');

    $container->compile();

    $dumper = new PhpDumper($container);

    file_put_contents(
        $file,
        $dumper->dump(array(
            'class' => 'EcommerceServiceContainer'
        ))
    );

}

Heyday\Ecommerce\ServiceStore::set($container);