<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * CompilerPass namespace
 */
namespace Heystack\Core\DependencyInjection\CompilerPass;

use Heystack\Core\Services;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

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
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Services::SS_ORM_BACKEND)) {
            return;

        }

        $definition = $container->getDefinition(Services::SS_ORM_BACKEND);

        $taggedServices = $container->findTaggedServiceIds(Services::SS_ORM_BACKEND . '.reference_data_provider');

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addReferenceDataProvider',
                [new Reference($id)]
            );
        }
    }
}
