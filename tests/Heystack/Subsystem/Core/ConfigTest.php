<?php

namespace Heystack\Subsystem\Core;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $config;

    protected function setUp()
    {
        $this->config = new Config();
    }

    protected function tearDown()
    {
        $this->config = null;
    }

    public function testGetSetRemove()
    {
        $this->config->setConfig('test', 'hello');

        $this->assertEquals('hello', $this->config->getConfig('test'));

        $this->config->removeConfig('test');

        $this->assertEquals(false, $this->config->getConfig('test'));
    }
}
