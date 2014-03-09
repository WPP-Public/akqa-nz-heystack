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
class SchemaService implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Services::SCHEMA)) {
            return;
        }

        $definition = $container->getDefinition(Services::SCHEMA);

        foreach ($container->findTaggedServiceIds(Services::SCHEMA) as $id => $attrs) {
            $taggedDefinition = $container->getDefinition($id);
            
            foreach ($attrs as $attr) {
                if (is_array($attr)) {
                    if (isset($attr['reference'])) {
                        $taggedDefinition->addMethodCall('setReference', [$attr['reference']]);
                    }
                    if (isset($attr['replace'])) {
                        $taggedDefinition->addMethodCall('setReplace', [$attr['replace']]);
                    }
                }

                $definition->addMethodCall(
                    'addSchema',
                    [new Reference($id)]
                );
            }
        }
    }
}
