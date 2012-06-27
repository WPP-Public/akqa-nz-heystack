<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Compiler\MergeExtensionConfigurationPass;

use Ecommerce\Subsystem\Core\MysiteContainerExtension;
use Ecommerce\Subsystem\Core\ContainerExtension;
use Ecommerce\Subsystem\Core\State\State;
use Ecommerce\Subsystem\Core\Config;

$file = ECOMMERCE_BASE_PATH . '/cache/container.php';

if (file_exists($file) && !isset($_GET['flush'])) {

    require_once $file;
    $container = new EcommerceServiceContainer();

} else {

    $loader = new YamlFileLoader(
        $container = new ContainerBuilder(),
        new FileLocator(array(
            BASE_PATH . '/mysite/config/',
            ECOMMERCE_BASE_PATH . '/config/'
        ))
    );

    foreach (Config::getContainerExtensions() as $extension) {
        
        $container->registerExtension(new $extension);

    }

    $loader->load('services.yml');

    $container->compile();

    $dumper = new PhpDumper($container);

    file_put_contents(
        $file,
        $dumper->dump(array(
            'class' => 'EcommerceServiceContainer'
        ))
    );

}

Ecommerce\Subsystem\Core\ServiceStore::set($container);