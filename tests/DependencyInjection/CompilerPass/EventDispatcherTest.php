<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Heystack\Core\Services;
use Symfony\Component\DependencyInjection\Reference;

class EventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\EventDispatcher::process
     */
    public function testProcessHasDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');

        $definition
            ->expects($this->once())
            ->method('addMethodCall')
            ->with('addSubscriber', [new Reference('test')]);

        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::EVENT_DISPATCHER)
            ->will($this->returnValue(true));

        $container
            ->expects($this->once())
            ->method('getDefinition')
            ->with(Services::EVENT_DISPATCHER)
            ->will($this->returnValue($definition));

        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with(Services::EVENT_DISPATCHER.'.subscriber')
            ->will($this->returnValue([
                'test' => [true]
            ]));

        (new EventDispatcher())->process($container);
    }

    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\EventDispatcher::process
     */
    public function testProcessNoDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::EVENT_DISPATCHER)
            ->will($this->returnValue(false));

        $container
            ->expects($this->never())
            ->method('getDefinition');

        (new EventDispatcher())->process($container);
    }
}