<?php

/**
 * This file is part of the Heystack package
 * 
 * @package Heystack
 */

/**
 * Core namespace
 */
namespace Heystack\Subsystem\Core;

use Symfony\Component\DependencyInjection\Container;

/**
 * Service store allows acess to the Symfony dependency injection container from SilverStripe code.
 * 
 * SilverStripe services like controllers can't easily be created and accessed 
 * by SilverStripe through the dependency injection component, and so, a global
 * store is made available for SilverStripe-centric classes that need to access
 * services
 * 
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * 
 */
class ServiceStore
{
    /**
     * Stores the service container
     */
    private static $serviceContainer = null;

    /**
     * Returns the service container
     * @return Container
     */
    public static function get()
    {

        return self::$serviceContainer;

    }
    /**
     * Sets the service container
     * @param Container $container 
     * @return null
     */
    public static function set(Container $container)
    {

        self::$serviceContainer = $container;

    }
    /**
     * Gets a specific service by name from the service container
     * @param string $service 
     * @return mixed
     */
    public static function getService($service)
    {

        return self::$serviceContainer->get($service);

    }

}
