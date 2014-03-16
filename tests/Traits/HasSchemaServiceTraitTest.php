<?php

namespace Heystack\Core\Traits;

use Heystack\Core\DataObjectSchema\SchemaService;

/**
 * @package Heystack\Core\Traits
 */
class HasSchemaServiceTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Heystack\Core\Traits\HasSchemaServiceTrait::setSchemaService
     */
    public function testCanSetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasSchemaServiceTrait');
        $o->setSchemaService(
            $m = new SchemaService()
        );
        $this->assertAttributeEquals(
            $m,
            'schemaService',
            $o
        );
    }

    /**
     * @covers Heystack\Core\Traits\HasSchemaServiceTrait::getSchemaService
     * @covers Heystack\Core\Traits\HasSchemaServiceTrait::setSchemaService
     */
    public function testCanGetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasSchemaServiceTrait');
        
        $this->assertNull(
            $o->getSchemaService()
        );

        $o->setSchemaService(
            $m = new SchemaService()
        );

        $this->assertEquals(
            $m,
            $o->getSchemaService()
        );
    }
} 