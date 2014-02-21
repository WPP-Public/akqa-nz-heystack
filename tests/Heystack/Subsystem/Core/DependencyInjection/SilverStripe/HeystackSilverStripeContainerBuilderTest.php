<?php

namespace Heystack\Subsystem\Core\DependencyInjection\SilverStripe;

class HeystackSilverStripeContainerBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $containerBuilder;

    protected function setUp()
    {
        $this->containerBuilder = new HeystackSilverStripeContainerBuilder();
    }

    protected function tearDown()
    {
        $this->containerBuilder = null;
    }

    public function testHasSilverStripeService()
    {
        $this->assertTrue($this->containerBuilder->has('silverstripe.controller'));
    }

    public function testNoHeystackService()
    {
        $this->assertFalse($this->containerBuilder->has('state'));
    }
}
