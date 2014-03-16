<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Reference;

class AutoInjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::process
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::getReflectionClass
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::hasConfig
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::addInterfaceProvider
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::addClassProvider
     */
    public function testProcessProvides()
    {
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $definitionMock = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $definition2Mock = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $pBagMock = $this->getMock('Symfony\Component\DependencyInjection\ParameterBag\ParameterBag');

        $pBagMock
            ->expects($this->at(0))
            ->method('resolveValue')
            ->with(__NAMESPACE__.'\ExampleClassExtender')
            ->will($this->returnValue(__NAMESPACE__.'\ExampleClassExtender'));
        $pBagMock
            ->expects($this->at(1))
            ->method('resolveValue')
            ->with('stdClass')
            ->will($this->returnValue('stdClass'));

        $definitionMock
            ->expects($this->once())
            ->method('getClass')
            ->will($this->returnValue(__NAMESPACE__.'\ExampleClassExtender'));

        $definition2Mock
            ->expects($this->once())
            ->method('getClass')
            ->will($this->returnValue('stdClass'));

        $containerMock
            ->expects($this->at(0))
            ->method('findTaggedServiceIds')
            ->with(AutoInject::TAG_AUTO_INJECT_PROVIDES)
            ->will($this->returnValue([
                'test' => [ [ 'all' => true ] ],
                'test2' => [ [ 'all' => true ] ]
            ]));

        $containerMock
            ->expects($this->any())
            ->method('getParameterBag')
            ->will($this->returnValue($pBagMock));
        
        $containerMock
            ->expects($this->at(1))
            ->method('getDefinition')
            ->with('test')
            ->will($this->returnValue($definitionMock));

        $containerMock
            ->expects($this->at(3))
            ->method('getDefinition')
            ->with('test2')
            ->will($this->returnValue($definition2Mock));

        $containerMock
            ->expects($this->at(5))
            ->method('findTaggedServiceIds')
            ->with(AutoInject::TAG_AUTO_INJECT)
            ->will($this->returnValue([]));


        $a = new AutoInject();
        $a->process($containerMock);
        $this->assertAttributeEquals(
            [
                __NAMESPACE__.'\ExampleClassExtender' => [
                    new Reference('test')
                ],
                __NAMESPACE__.'\ExampleInterfaceImplementor' => [
                    new Reference('test')
                ],
                'stdClass' => [
                    new Reference('test2')
                ]
            ],
            'classProviders',
            $a
        );
        $this->assertAttributeEquals(
            [
                __NAMESPACE__.'\ExampleInterface' => [
                    new Reference('test')
                ]
            ],
            'interfaceProviders',
            $a
        );
        
        return $a;
    }

    /**
     * @depends testProcessProvides
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::process
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::getReflectionClass
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::hasConfig
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::getProvider
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::getProviders
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::getInjectableMethods
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::assertSingleInterfaceProvider
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::assertSingleClassProvider
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::isMethodIncluded
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\AutoInject::isMethodExcluded
     */
    public function testProcessInject(AutoInject $autoinject)
    {
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $definitionMock = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $pBagMock = $this->getMock('Symfony\Component\DependencyInjection\ParameterBag\ParameterBag');
        
        $pBagMock
            ->expects($this->once())
            ->method('resolveValue')
            ->with(__NAMESPACE__.'\ExampleAutoInjectable')
            ->will($this->returnValue(__NAMESPACE__.'\ExampleAutoInjectable'));

        $definitionMock
            ->expects($this->at(0))
            ->method('getClass')
            ->will($this->returnValue(__NAMESPACE__.'\ExampleAutoInjectable'));

        $definitionMock
            ->expects($this->at(2))
            ->method('setArguments')
            ->with([
                new Reference('test'),
                new Reference('test'),
                new Reference('test'),
                null
            ]);
        
        $definitionMock
            ->expects($this->at(3))
            ->method('addMethodCall')
            ->with('setTest', [new Reference('test2')]);

        $definitionMock
            ->expects($this->at(4))
            ->method('addMethodCall')
            ->with('addTest', [new Reference('test')]);

        $containerMock
            ->expects($this->once())
            ->method('getParameterBag')
            ->will($this->returnValue($pBagMock));

        $containerMock
            ->expects($this->once())
            ->method('getDefinition')
            ->with('test3')
            ->will($this->returnValue($definitionMock));

        $containerMock
            ->expects($this->at(0))
            ->method('findTaggedServiceIds')
            ->with(AutoInject::TAG_AUTO_INJECT_PROVIDES)
            ->will($this->returnValue([]));

        $containerMock
            ->expects($this->at(1))
            ->method('findTaggedServiceIds')
            ->with(AutoInject::TAG_AUTO_INJECT)
            ->will($this->returnValue([
                'test3' => [ [ 'all' => true ] ]
            ]));
        
        $autoinject->process($containerMock);
        
    }
}

interface ExampleInterface {}
class ExampleInterfaceImplementor implements ExampleInterface {}
class ExampleClassExtender extends ExampleInterfaceImplementor {}
class ExampleAutoInjectable
{
    public function __construct(
        ExampleInterface $a,
        ExampleInterfaceImplementor $b,
        ExampleClassExtender $c = null,
        \Exception $d = null
    )
    {
    }
    
    public function setTest(\stdClass $a) {}
    public function addTest(ExampleInterface $a) {}
}