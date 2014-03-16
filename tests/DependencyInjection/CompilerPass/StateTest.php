<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

class StateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\State::process
     */
    public function testProcessHasDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');

        $definition
            ->expects($this->once())
            ->method('addMethodCall')
            ->with('restoreState');

        $container
            ->expects($this->once())
            ->method('getDefinition')
            ->with('test')
            ->will($this->returnValue($definition));

        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('state.restore')
            ->will($this->returnValue([
                'test' => null
            ]));

        (new State())->process($container);
    }
}