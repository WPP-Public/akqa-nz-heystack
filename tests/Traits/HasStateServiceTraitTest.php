<?php

namespace Heystack\Core\Traits;


/**
 * @package Heystack\Core\Traits
 */
class HasStateServiceTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Heystack\Core\Traits\HasStateServiceTrait::setStateService
     */
    public function testCanSetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasStateServiceTrait');
        $o->setStateService(
            $m = $this->getMockBuilder('Heystack\Core\State\State')
                ->disableOriginalConstructor()->getMock()
        );
        $this->assertAttributeEquals(
            $m,
            'stateService',
            $o
        );
    }

    /**
     * @covers Heystack\Core\Traits\HasStateServiceTrait::getStateService
     * @covers Heystack\Core\Traits\HasStateServiceTrait::setStateService
     */
    public function testCanGetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasStateServiceTrait');
        
        $this->assertNull(
            $o->getStateService()
        );

        $o->setStateService(
            $m = $this->getMockBuilder('Heystack\Core\State\State')
                ->disableOriginalConstructor()->getMock()
        );

        $this->assertEquals(
            $m,
            $o->getStateService()
        );
    }
} 