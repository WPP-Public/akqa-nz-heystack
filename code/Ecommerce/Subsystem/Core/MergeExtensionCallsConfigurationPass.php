<?php

namespace Ecommerce\Subsystem\Core;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Merges extension configs into the container builder
 *
 * @author Fabien Potencier <fabien@symfony.com>
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
            
            foreach ($tmpContainer->getDefinitions() as $name => $definition) {
                
                $calls = $definition->getMethodCalls();
                
                if (!is_null($calls) && $container->hasDefinition($name)) {
                    
                    $parentDefinition = $container->getDefinition($name);
                    
                    foreach ($calls as $call) {
                        
                        if (!$parentDefinition->hasMethodCall($call[0]))
                            $parentDefinition->addMethodCall($call[0], $call[1]);
                        
                    }
                    
                }
                
            }
            
        }

    }
}
