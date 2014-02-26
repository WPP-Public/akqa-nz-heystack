<?php

namespace Heystack\Core\DependencyInjection\SilverStripe;

class HeystackSilverStripeContainerTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    protected function setUp()
    {
        $controller = $this->getMock('\Controller');

        $injector = $this->getMock('\Injector');
        $injector->expects($this->any())
            ->method('get')
            ->will($this->returnValue($controller));

        $this->container = new HeystackSilverStripeContainer();
        $this->container->setInjector($injector);
    }

    protected function tearDown()
    {
        $this->container = null;
    }

    public function testHasSilverStripeService()
    {
        $this->assertTrue($this->container->get('silverstripe.controller') instanceof \Controller);
    }

    /**
     * @expectedException Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function testHasNoHeystackService()
    {
        $this->container->get('state');
    }
}
