<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Heystack\Core\Services;
use Symfony\Component\DependencyInjection\Reference;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\Command::process
     */
    public function testProcessHasDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');

        $definition
            ->expects($this->once())
            ->method('addMethodCall')
            ->with('add', [new Reference('test')]);
        
        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::CONSOLE_APPLICATION)
            ->will($this->returnValue(true));
        
        $container
            ->expects($this->once())
            ->method('getDefinition')
            ->with(Services::CONSOLE_APPLICATION)
            ->will($this->returnValue($definition));
        
        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with(Services::CONSOLE_APPLICATION.'.command')
            ->will($this->returnValue([
                'test' => [true]
            ]));
        
        (new Command())->process($container);
    }

    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\Command::process
     */
    public function testProcessNoDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::CONSOLE_APPLICATION)
            ->will($this->returnValue(false));

        $container
            ->expects($this->never())
            ->method('getDefinition');

        (new Command())->process($container);
    }
} 