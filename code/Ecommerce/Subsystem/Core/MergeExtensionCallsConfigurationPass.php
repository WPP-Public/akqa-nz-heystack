<?php

namespace Ecommerce\Subsystem\Core;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Merges extension calls into the container builder
 *
 * @author Stevie Mayhew & Cam Spiers
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
