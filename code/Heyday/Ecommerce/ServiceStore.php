<?php

namespace Heyday\Ecommerce;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;

class ServiceStore
{

    private static $serviceContainer = null;

    public static function get()
    {

        return self::$serviceContainer;

    }

    public static function set(Container $container)
    {

        self::$serviceContainer = $container;
        
    }

    public static function getService($service)
    {

        return self::$serviceContainer->get($service);

    }

}