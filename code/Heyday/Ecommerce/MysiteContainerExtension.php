<?php

namespace Heyday\Ecommerce;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MysiteContainerExtension implements ExtensionInterface
{

    public function load(array $config, ContainerBuilder $container)
    {

        if (file_exists(BASE_PATH . '/mysite/config/services.yml')) {

            $loader = new YamlFileLoader(
                $container,
                new FileLocator(BASE_PATH . '/mysite/config/')
            );

            $loader->load('services.yml');

        }

    }

    public function getNamespace()
    {
        return 'mysite';
    }

    public function getXsdValidationBasePath()
    {
        return false;
    }

    public function getAlias()
    {
        return 'mysite';
    }

}