<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Heystack\Core\Services;
use Symfony\Component\DependencyInjection\Reference;

class SilverStripeOrmTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\SilverStripeOrm::process
     */
    public function testProcessHasDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');

        $definition
            ->expects($this->once())
            ->method('addMethodCall')
            ->with('addReferenceDataProvider', [new Reference('test')]);
        
        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::SS_ORM_BACKEND)
            ->will($this->returnValue(true));
        
        $container
            ->expects($this->once())
            ->method('getDefinition')
            ->with(Services::SS_ORM_BACKEND)
            ->will($this->returnValue($definition));
        
        $container
            ->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with(Services::SS_ORM_BACKEND.'.reference_data_provider')
            ->will($this->returnValue([
                'test' => [true]
            ]));
        
        (new SilverStripeOrm())->process($container);
    }

    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\SilverStripeOrm::process
     */
    public function testProcessNoDefinition()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $container
            ->expects($this->once())
            ->method('hasDefinition')
            ->with(Services::SS_ORM_BACKEND)
            ->will($this->returnValue(false));

        $container
            ->expects($this->never())
            ->method('getDefinition');

        (new SilverStripeOrm())->process($container);
    }
} 