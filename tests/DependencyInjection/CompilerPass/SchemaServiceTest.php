<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Heystack\Core\Services;
use Symfony\Component\DependencyInjection\Reference;

class SchemaServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\SchemaService::process
     */
    public function testProcessHasDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $testDefinition = $this->getMock('Symfony\Component\DependencyInjection\Definition');

        $testDefinition
            ->expects($this->at(0))
            ->method('addMethodCall')
            ->with('setReference', [true]);
        $testDefinition
            ->expects($this->at(1))
            ->method('addMethodCall')
            ->with('setReplace', [true]);

        $definition
            ->expects($this->once())
            ->method('addMethodCall')
            ->with('addSchema', [new Reference('test')]);

        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::SCHEMA)
            ->will($this->returnValue(true));

        $container
            ->expects($this->at(1))
            ->method('getDefinition')
            ->with(Services::SCHEMA)
            ->will($this->returnValue($definition));

        $container
            ->expects($this->at(3))
            ->method('getDefinition')
            ->with('test')
            ->will($this->returnValue($testDefinition));

        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with(Services::SCHEMA)
            ->will($this->returnValue([
                'test' => [
                    ['replace' => true, 'reference' => true]
                ]
            ]));

        (new SchemaService())->process($container);
    }

    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\SchemaService::process
     */
    public function testProcessNoDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::SCHEMA)
            ->will($this->returnValue(false));

        $container
            ->expects($this->never())
            ->method('getDefinition');

        (new SchemaService())->process($container);
    }
}