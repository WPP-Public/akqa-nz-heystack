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
class EventDispatcher implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {

        if (!$container->hasDefinition(Services::EVENT_DISPATCHER)) {
            return;
        }

        $definition = $container->getDefinition(Services::EVENT_DISPATCHER);

        $taggedServices = $container->findTaggedServiceIds(Services::EVENT_DISPATCHER . '.subscriber');

        foreach ($taggedServices as $id => $attributes) {

            $definition->addMethodCall(
                'addSubscriber',
                [new Reference($id)]
            );

        }

    }
}
