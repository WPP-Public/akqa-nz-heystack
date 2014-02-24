<?php

namespace Heystack\Core;

class ServiceStoreTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->container = $this->getMock(
            'Symfony\Component\DependencyInjection\Container',
            [
                'getTestService'
            ]
        );
        $this->container->expects($this->any())
            ->method('getTestService')
            ->will($this->returnValue(new \stdClass));
    }

    public function testSetGet()
    {
        ServiceStore::set($this->container);
        $this->assertEquals($this->container, ServiceStore::get());

    }

    public function testGetService()
    {
        ServiceStore::set($this->container);
        $this->assertInternalType('object', ServiceStore::getService('test'));
    }
}
