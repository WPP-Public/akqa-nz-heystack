<?php

namespace Heystack\Core\Traits;

use Heystack\Core\EventDispatcher;

/**
 * @package Heystack\Core\Traits
 */
class HasEventServiceTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Heystack\Core\Traits\HasEventServiceTrait::setEventService
     */
    public function testCanSetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasEventServiceTrait');
        $o->setEventService(
            $m = new EventDispatcher()
        );
        $this->assertAttributeEquals(
            $m,
            'eventService',
            $o
        );
    }

    /**
     * @covers Heystack\Core\Traits\HasEventServiceTrait::getEventService
     * @covers Heystack\Core\Traits\HasEventServiceTrait::setEventService
     */
    public function testCanGetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasEventServiceTrait');
        
        $this->assertNull(
            $o->getEventService()
        );

        $o->setEventService(
            $m = new EventDispatcher()
        );

        $this->assertEquals(
            $m,
            $o->getEventService()
        );
    }
} 