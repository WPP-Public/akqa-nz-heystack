<?php

namespace Heystack\Core\Traits;

/**
 * @package Heystack\Core\Traits
 */
class HasLoggerServiceTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Heystack\Core\Traits\HasLoggerServiceTrait::setLoggerService
     */
    public function testCanSetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasLoggerServiceTrait');
        $o->setLoggerService(
            $m = $this->getMock('Psr\Log\LoggerInterface')
        );
        $this->assertAttributeEquals(
            $m,
            'loggerService',
            $o
        );
    }

    /**
     * @covers Heystack\Core\Traits\HasLoggerServiceTrait::getLoggerService
     * @covers Heystack\Core\Traits\HasLoggerServiceTrait::setLoggerService
     */
    public function testCanGetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasLoggerServiceTrait');
        
        $this->assertNull(
            $o->getLoggerService()
        );

        $o->setLoggerService(
            $m = $this->getMock('Psr\Log\LoggerInterface')
        );

        $this->assertEquals(
            $m,
            $o->getLoggerService()
        );
    }
} 