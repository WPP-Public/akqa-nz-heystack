<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class State
 * @package Heystack\Core\DependencyInjection\CompilerPass
 */
class State implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('state.restore') as $id => $_) {
            $container->getDefinition($id)->addMethodCall(
                'restoreState'
            );
        }
    }
}
