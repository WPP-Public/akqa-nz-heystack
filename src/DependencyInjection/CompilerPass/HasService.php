<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class HasService
 * @package Heystack\Core\DependencyInjection\CompilerPass
 */
abstract class HasService implements CompilerPassInterface
{
    /**
     * The name of the service in the container
     * @return string
     */
    abstract protected function getServiceName();

    /**
     * The method name used to set the service
     * @return string
     */
    abstract protected function getServiceSetterName();

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        $serviceName = $this->getServiceName();

        if (!$serviceName || !$container->hasDefinition($serviceName)) {
            return;
        }

        foreach ($container->findTaggedServiceIds(sprintf('set.%s', $serviceName)) as $id => $_) {
            $container->getDefinition($id)->addMethodCall(
                $this->getServiceSetterName(),
                [new Reference($serviceName)]
            );
        }
    }
}
