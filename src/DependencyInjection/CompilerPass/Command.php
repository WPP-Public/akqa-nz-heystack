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
 *
 * @copyright  Heyday
 * @author     Cam Spiers
 * @package    Heystack
 */
class Command implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Services::CONSOLE_APPLICATION)) {
            return;
        }

        $definition = $container->getDefinition(Services::CONSOLE_APPLICATION);

        $taggedServices = $container->findTaggedServiceIds(Services::CONSOLE_APPLICATION . '.command');

        foreach ($taggedServices as $id => $attributes) {
            foreach ($attributes as $_) {
                $definition->addMethodCall(
                    'add',
                    [new Reference($id)]
                );
            }
        }

    }
}
