<?php

namespace Heystack\Core\DependencyInjection\SilverStripe;

use Heystack\Core\State\State;

class HeystackInjectionCreatorTest extends \PHPUnit_Framework_TestCase
{
    protected $injectionCreator;

    protected function setUp()
    {
        $stateService = $this->getMockBuilder('Heystack\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();

        $container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $container->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));
        $container->expects($this->any())
            ->method('get')
            ->will($this->returnValue($stateService));

        $this->injectionCreator = new HeystackInjectionCreator($container);
    }

    protected function tearDown()
    {
        $this->injectionCreator = null;
    }

    public function testCreateHeystackService()
    {
        $this->assertTrue($this->injectionCreator->create('heystack.state') instanceof State);
    }

    /**
     * @expectedException ReflectionException
     */
    public function testCreateNonExistentService()
    {
        $this->injectionCreator->create('nonexistent_service');
    }

    public function testCreateSilverStripeService()
    {
        $this->assertTrue($this->injectionCreator->create('Controller') instanceof \Controller);
    }
}
