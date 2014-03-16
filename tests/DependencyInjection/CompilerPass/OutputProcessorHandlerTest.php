<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Heystack\Core\Services;
use Symfony\Component\DependencyInjection\Reference;

class OutputProcessorHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\OutputProcessorHandler::process
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
            ->with(Services::OUTPUT_PROCESSOR_HANDLER)
            ->will($this->returnValue(true));
        
        $container
            ->expects($this->once())
            ->method('getDefinition')
            ->with(Services::OUTPUT_PROCESSOR_HANDLER)
            ->will($this->returnValue($definition));
        
        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with(Services::OUTPUT_PROCESSOR_HANDLER.'.processor')
            ->will($this->returnValue([
                'test' => [true]
            ]));
        
        (new OutputProcessorHandler())->process($container);
    }

    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\OutputProcessorHandler::process
     */
    public function testProcessNoDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::OUTPUT_PROCESSOR_HANDLER)
            ->will($this->returnValue(false));

        $container
            ->expects($this->never())
            ->method('getDefinition');

        (new OutputProcessorHandler())->process($container);
    }
} 