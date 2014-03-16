<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Heystack\Core\Services;
use Symfony\Component\DependencyInjection\Reference;

class InputProcessorHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\InputProcessorHandler::process
     */
    public function testProcessHasDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');

        $definition
            ->expects($this->once())
            ->method('addMethodCall')
            ->with('addProcessor', [new Reference('test')]);
        
        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::INPUT_PROCESSOR_HANDLER)
            ->will($this->returnValue(true));
        
        $container
            ->expects($this->once())
            ->method('getDefinition')
            ->with(Services::INPUT_PROCESSOR_HANDLER)
            ->will($this->returnValue($definition));
        
        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with(Services::INPUT_PROCESSOR_HANDLER.'.processor')
            ->will($this->returnValue([
                'test' => [true]
            ]));
        
        (new InputProcessorHandler())->process($container);
    }

    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\InputProcessorHandler::process
     */
    public function testProcessNoDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::INPUT_PROCESSOR_HANDLER)
            ->will($this->returnValue(false));

        $container
            ->expects($this->never())
            ->method('getDefinition');

        (new InputProcessorHandler())->process($container);
    }
} 