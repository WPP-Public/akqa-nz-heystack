<?php

namespace Heystack\Core;

/**
 * @package Heystack\Core
 */
class EventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\EventDispatcher::getEnabled
     */
    public function testGetEnabled()
    {
        $e = new EventDispatcher();
        $this->assertTrue($e->getEnabled());
    }
    
    /**
     * @covers \Heystack\Core\EventDispatcher::setEnabled
     */
    public function testSetEnabled()
    {
        $e = new EventDispatcher();
        $this->assertAttributeEquals(true, 'enabled', $e);
        $e->setEnabled(false);
        $this->assertAttributeEquals(false, 'enabled', $e);
    }

    /**
     * @covers \Heystack\Core\EventDispatcher::dispatch
     * @covers \Heystack\Core\EventDispatcher::setEnabled
     */
    public function testDoesntDispatchWhenNotEnabled()
    {
        $fired = false;
        $e = new EventDispatcher();
        $e->addListener('test', function () use (&$fired) {
            $fired = true;
        });
        $e->setEnabled(false);
        $e->dispatch('test');
        $this->assertFalse($fired);
    }

    /**
     * @covers \Heystack\Core\EventDispatcher::dispatch
     */
    public function testDoesDispatchWhenEnabled()
    {
        $fired = false;
        $e = new EventDispatcher();
        $e->addListener('test', function () use (&$fired) {
            $fired = true;
        });
        $e->dispatch('test');
        $this->assertTrue($fired);
    }
}