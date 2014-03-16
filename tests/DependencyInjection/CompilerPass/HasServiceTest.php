<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Reference;

class HasServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\HasService::process
     */
    public function testProcessReturnsOnInvalidValues()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $p = $this->getMockForAbstractClass(__NAMESPACE__.'\HasService');
        
        $container
            ->expects($this->never())
            ->method('findTaggedServiceIds');
        
        $p->process($container);

        $p->expects($this->once())
            ->method('getServiceName')
            ->will($this->returnValue('test'));
        
        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with('test')
            ->will($this->returnValue(false));

        $p->process($container);
    }
    
    public function testProcessDoesAddMethodCall()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');

        $definition
            ->expects($this->once())
            ->method('addMethodCall')
            ->with('setTest', [new Reference('test')]);
        
        $p = $this->getMockForAbstractClass(__NAMESPACE__.'\HasService');

        $p->expects($this->once())
            ->method('getServiceName')
            ->will($this->returnValue('test'));
        $p->expects($this->once())
            ->method('getServiceSetterName')
            ->will($this->returnValue('setTest'));

        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('set.test')
            ->will($this->returnValue([
                'test' => null
            ]));

        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with('test')
            ->will($this->returnValue(true));
        
        $container
            ->expects($this->once())
            ->method('getDefinition')
            ->with('test')
            ->will($this->returnValue($definition));

        $p->process($container);
    }
}