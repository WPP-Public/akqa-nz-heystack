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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Merges extensions definition calls into the container builder.
 *
 * When there exists an extension which defines calls on an existing service,
 * this compiler pass will merge those calls without overwriting.
 *
 * @copyright  Heyday
 * @author Stevie Mayhew
 * @author Cam Spiers
 * @package Heystack
 */
class MergeExtensionCallsConfigurationPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {

        foreach ($container->getExtensions() as $name => $extension) {

            if (!$config = $container->getExtensionConfig($name)) {
                // this extension was not called
                continue;
            }

            $config = $container->getParameterBag()->resolveValue($config);

            $tmpContainer = new ContainerBuilder($container->getParameterBag());

            $extension->load($config, $tmpContainer);

            $tmpDefintions = $tmpContainer->getDefinitions();

            foreach ($tmpDefintions as $definitionName => $definition) {

                if ($container->hasDefinition($definitionName)) {

                    $parentDefinition = $container->getDefinition($definitionName);

                    $calls = $definition->getMethodCalls();
                    $parentCalls = $parentDefinition->getMethodCalls();

                    foreach ($calls as $call) {

                        if (array_search($call, $parentCalls) === false) {

                            $parentDefinition->addMethodCall($call[0], $call[1]);

                        }

                    }

                }

            }

        }

    }
}
