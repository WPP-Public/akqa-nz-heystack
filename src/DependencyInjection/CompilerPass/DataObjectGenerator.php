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
class DataObjectGenerator implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Services::DATAOBJECT_GENERATOR)) {
            return;

        }

        $definition = $container->getDefinition(Services::DATAOBJECT_GENERATOR);

        $taggedServices = $container->findTaggedServiceIds(Services::DATAOBJECT_GENERATOR . '.schema');

        foreach ($taggedServices as $id => $attributes) {

            $params = reset($attributes);

            $schemaParameters = [new Reference($id)];

            if (isset($params) && is_array($params) && count($params)) {

                $reference = isset($params['reference']) ? $params['reference'] : false;

                $schemaParameters[] = $reference;

                $force = isset($params['force']) ? $params['force'] : false;

                $schemaParameters[] = $force;

            }

            $definition->addMethodCall(
                'addSchema',
                $schemaParameters
            );

        }

    }
}
