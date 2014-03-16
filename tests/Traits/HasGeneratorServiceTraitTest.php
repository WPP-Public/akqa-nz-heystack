<?php

namespace Heystack\Core\Traits;

/**
 * @package Heystack\Core\Traits
 */
class HasGeneratorServiceTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Heystack\Core\Traits\HasGeneratorServiceTrait::setGeneratorService
     */
    public function testCanSetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasGeneratorServiceTrait');
        $o->setGeneratorService(
            $m = $this
                ->getMockBuilder('Heystack\Core\DataObjectGenerate\DataObjectGenerator')
                ->disableOriginalConstructor()->getMock()
        );
        $this->assertAttributeEquals(
            $m,
            'generatorService',
            $o
        );
    }

    /**
     * @covers Heystack\Core\Traits\HasGeneratorServiceTrait::getGeneratorService
     * @covers Heystack\Core\Traits\HasGeneratorServiceTrait::setGeneratorService
     */
    public function testCanGetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasGeneratorServiceTrait');
        
        $this->assertNull(
            $o->getGeneratorService()
        );

        $o->setGeneratorService(
            $m = $this
                ->getMockBuilder('Heystack\Core\DataObjectGenerate\DataObjectGenerator')
                ->disableOriginalConstructor()->getMock()
        );

        $this->assertEquals(
            $m,
            $o->getGeneratorService()
        );
    }
} 