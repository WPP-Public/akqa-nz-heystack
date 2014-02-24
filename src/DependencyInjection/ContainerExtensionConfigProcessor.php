<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Core namespace
 */
namespace Heystack\Subsystem\Core\DependencyInjection;

use Heystack\Subsystem\Core\Exception\ConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Container extension config processor for Heystack.
 *
 * This is the base class for the ContainerExtension classes used throughout
 * the system and subsystems.
 *
 * @copyright  Heyday
 * @author     Cam Spiers <cameron@heyday.co.nz>
 * @author     Glenn Bautista <glenn@heyday.co.nz>
 * @package    Heystack
 *
 */
abstract class ContainerExtensionConfigProcessor
{
    /**
     * Meant to be called in at the tail end of the load function in the
     * inheriting container extension. This handles all the 'parameters'
     * defined in the services.yml file found in /mysite/config. It overrides
     * the parameters set in the subsystem's services.yml file
     *
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function processConfig(array $config, ContainerBuilder $container)
    {
        $config = array_pop($config);

        if (isset($config['parameters'])) {
            if (isset($config['parameters']) && count($config['parameters'])) {
                foreach ($config['parameters'] as $key => $value) {
                    $container->setParameter($key, $value);
                }
            }
        }

        $parameters = $container->getParameterBag()->all();

        foreach ($parameters as $name => $parameter) {
            if ($parameter === '$$$') {
                throw new ConfigurationException(
<<<MESSAGE
The parameter: $name still has the default value.
Please override in your /mysite/config/services.yml file
MESSAGE
                );
            }
        }
    }
}
