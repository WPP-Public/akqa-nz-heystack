<?php

namespace Heystack\Core\DataObjectSchema;

use Heystack\Core\Identifier\Identifier;

class SchemaServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DataObjectSchema\SchemaService::addSchema
     * @covers \Heystack\Core\DataObjectSchema\SchemaService::hasSchema
     */
    public function testCanAddBasicSchema()
    {
        $schema = $this->getMock('Heystack\Core\DataObjectSchema\SchemaInterface');

        $schema->expects($this->once())
            ->method('getIdentifier')
            ->will($this->returnValue(new Identifier('test')));

        $schema->expects($this->once())
            ->method('getReference')
            ->will($this->returnValue(false));

        $schemaService = new SchemaService();
        $schemaService->addSchema($schema);
        
        $this->assertAttributeEquals(
            ['test' => $schema],
            'schemas',
            $schemaService
        );
        
        return [$schemaService, $schema];
    }
    
    /**
     * @covers \Heystack\Core\DataObjectSchema\SchemaService::addSchema
     */
    public function testCanAddReferenceSchema()
    {
        $schema = $this->getMock('Heystack\Core\DataObjectSchema\SchemaInterface');

        $schema->expects($this->once())
            ->method('getIdentifier')
            ->will($this->returnValue(new Identifier('test')));

        $schema->expects($this->once())
            ->method('getReference')
            ->will($this->returnValue(true));

        $schemaService = new SchemaService();
        $schemaService->addSchema($schema);

        $this->assertAttributeEquals(
            ['test' => $schema],
            'referenceSchemas',
            $schemaService
        );
        
        return [$schemaService, $schema];
    }

    /**
     * @covers \Heystack\Core\DataObjectSchema\SchemaService::addSchema
     */
    public function testCanAddReplaceSchema()
    {
        $schema1 = $this->getMock('Heystack\Core\DataObjectSchema\SchemaInterface');
        $schema2 = $this->getMock('Heystack\Core\DataObjectSchema\SchemaInterface');

        $schema1->expects($this->once())
            ->method('getIdentifier')
            ->will($this->returnValue(new Identifier('test')));
        $schema2->expects($this->once())
            ->method('getIdentifier')
            ->will($this->returnValue(new Identifier('test')));

        $schema1->expects($this->once())
            ->method('getReference')
            ->will($this->returnValue(false));
        $schema2->expects($this->once())
            ->method('getReference')
            ->will($this->returnValue(false));

        $schema1->expects($this->once())
            ->method('mergeSchema')
            ->with($schema2);

        $schemaService = new SchemaService();
        $schemaService->addSchema($schema1);
        $schemaService->addSchema($schema2);
    }

    /**
     * @depends testCanAddBasicSchema
     * @covers \Heystack\Core\DataObjectSchema\SchemaService::getSchema
     */
    public function testCanGetSchema($args)
    {
        list($service, $schema) = $args;
        $this->assertEquals(
            $schema,
            $service->getSchema('test')
        );
    }

    /**
     * @depends testCanAddReferenceSchema
     * @covers \Heystack\Core\DataObjectSchema\SchemaService::getSchema
     */
    public function testCanGetReferenceSchema($args)
    {
        list($service, $schema) = $args;
        $this->assertEquals(
            $schema,
            $service->getSchema('test')
        );
    }

    /**
     * @depends testCanAddBasicSchema
     * @covers \Heystack\Core\DataObjectSchema\SchemaService::getSchema
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Schema 'fake' not found
     */
    public function testExceptionThrownCanGetSchema($args)
    {
        list($service, ) = $args;
        $service->getSchema('fake');
    }

    /**
     * @depends testCanAddBasicSchema
     * @covers \Heystack\Core\DataObjectSchema\SchemaService::getSchemas
     */
    public function testCanGetSchemas($args)
    {
        list($service, ) = $args;
        
        $this->assertEquals(
            $this->readAttribute($service, 'schemas'),
            $service->getSchemas()
        );
    }
}