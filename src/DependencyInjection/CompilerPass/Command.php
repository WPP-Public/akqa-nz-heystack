<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Heystack\Core\Services;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Collects commands that are tagged and adds them to the application
 * @copyright  Heyday
 * @author     Cam Spiers
 * @package    Heystack
 */
class Command implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
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
