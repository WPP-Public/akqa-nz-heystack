<?php

namespace Heyday\Ecommerce;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EcommerceContainerExtension implements ExtensionInterface
{

    public function load(array $config, ContainerBuilder $container)
    {

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(HEYDAY_ECOMMERCE_BASE_PATH . '/config/')
        );

        $loader->load('services.yml');

    }

    public function getNamespace()
    {
        return 'ecommerce';
    }

    public function getXsdValidationBasePath()
    {
        return false;
    }

    public function getAlias()
    {
        return 'ecommerce';
    }

}