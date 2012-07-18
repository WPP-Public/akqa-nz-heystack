<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

use Heystack\Subsystem\Core\MergeExtensionCallsConfigurationPass;
use Heystack\Subsystem\Core\MergeExtensionArgumentsConfigurationPass;
use Heystack\Subsystem\Core\Config;

$file = HEYSTACK_BASE_PATH . '/cache/container.php';

if (file_exists($file) && !isset($_GET['flush'])) {

    require_once $file;
    $container = new HeystackServiceContainer();

} else {

    $loader = new YamlFileLoader(
        $container = new ContainerBuilder(),
        new FileLocator(array(
            BASE_PATH . '/mysite/config/',
            HEYSTACK_BASE_PATH . '/config/'
        ))
    );

    $extensions = require HEYSTACK_BASE_PATH . '/config/extensions.php';

    if ($extensions) {

        foreach ($extensions as $extension) {

            $container->registerExtension(new $extension);

        }

    }

    $loader->load('services.yml');

    $container->addCompilerPass(new MergeExtensionCallsConfigurationPass());
    $container->addCompilerPass(new MergeExtensionArgumentsConfigurationPass());

    $container->compile();

    $dumper = new PhpDumper($container);

    file_put_contents(
        $file,
        $dumper->dump(array(
            'class' => 'HeystackServiceContainer'
        ))
    );

}

Heystack\Subsystem\Core\ServiceStore::set($container);

return $container;
