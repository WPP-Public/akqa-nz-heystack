<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * CompilerPass namespace
 */
namespace Heystack\Subsystem\Core\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

use Heystack\Subsystem\Core\Services;

/**
 * Merges extensions definition calls into the container builder.
 *
 * When there exists an extension which defines calls on an existing service,
 * this compiler pass will merge those calls without overwriting.
 *
 * @copyright  Heyday
 * @author     Glenn Bautista
 * @package    Heystack
 */
class SilverStripeOrm implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {

        if (!$container->hasDefinition(Services::SS_ORM_BACKEND)) {

            return;

        }

        $definition = $container->getDefinition(Services::SS_ORM_BACKEND);

        $taggedServices = $container->findTaggedServiceIds(Services::SS_ORM_BACKEND . '.data_provider');

        foreach ($taggedServices as $id => $attributes) {

            $definition->addMethodCall(
                'addDataProvider',
                array(new Reference($id))
            );

        }

    }
}
