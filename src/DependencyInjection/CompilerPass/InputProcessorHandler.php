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
class InputProcessorHandler implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container)
    {

        if (!$container->hasDefinition(Services::INPUT_PROCESSOR_HANDLER)) {
            return;

        }

        $definition = $container->getDefinition(Services::INPUT_PROCESSOR_HANDLER);

        $taggedServices = $container->findTaggedServiceIds(Services::INPUT_PROCESSOR_HANDLER . '.processor');

        foreach ($taggedServices as $id => $attributes) {

            $definition->addMethodCall(
                'addProcessor',
                [new Reference($id)]
            );

        }

    }
}
