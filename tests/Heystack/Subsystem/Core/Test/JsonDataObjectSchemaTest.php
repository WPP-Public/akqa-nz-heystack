<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Generate\JsonDataObjectSchema;

class JsonDataObjectSchemaTest extends \PHPUnit_Framework_TestCase
{

    protected $state;
    protected $schema;

    protected function setUp()
    {
        $this->state = $this->getMockBuilder('Heystack\Subsystem\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();

        $this->schema = new JsonDataObjectSchema(
            'tests/Heystack/Subsystem/Core/Test/schemas/test_schema.json',
            $this->state
        );

    }

    protected function tearDown()
    {

        $this->state = null;
        $this->schema = null;

    }

    public function testSchema()
    {

        $message = null;

        try {

            new JsonDataObjectSchema('fake_file.json', $this->state);

        } catch (\Exception $e) {

            $message = $e->getMessage();

        }

        $this->assertEquals('Configuration Error: File doesn\'t exist', $message);

        $this->assertEquals('Test', $this->schema->getIdentifier()->getFull());

        $this->assertEquals('test', $this->schema->getDataProviderIdentifier());

        $this->assertEquals(array(
            'Test' => 'Text'
        ), $this->schema->getFlatStorage());

        $this->assertEquals(array(), $this->schema->getRelatedStorage());

        $this->assertEquals(array(), $this->schema->getParentStorage());

        $this->assertEquals(array(
            'Tests' => '+Test'
        ), $this->schema->getChildStorage());

    }

    public function testSchemaMerge()
    {

        $this->schema->mergeSchema(new JsonDataObjectSchema('tests/Heystack/Subsystem/Core/Test/schemas/test_schema2.json', $this->state));

        $this->assertEquals(array(
            'Test' => 'Text',
            'Test2' => 'Text'
        ), $this->schema->getFlatStorage());

        $this->assertEquals(array(
            'Tests' => '+Test',
            'Tests2' => '+Test2'
        ), $this->schema->getChildStorage());

    }

}
