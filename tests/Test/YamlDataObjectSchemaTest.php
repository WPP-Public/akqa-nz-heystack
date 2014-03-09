<?php

namespace Heystack\Core\Test;

use Heystack\Core\DataObjectGenerate\YamlDataObjectSchema;

use Heystack\Core\State\State;

class YamlDataObjectSchemaTest extends \PHPUnit_Framework_TestCase
{

    protected $state;
    protected $schema;

    protected function setUp()
    {
        $this->state = $this->getMockBuilder('Heystack\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();

        $this->schema = new YamlDataObjectSchema('tests/Heystack/Subsystem/Core/Test/schemas/test_schema.yml', $this->state);

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

            new YamlDataObjectSchema('fake_file.yml', $this->state);

        } catch (\Exception $e) {

            $message = $e->getMessage();

        }

        $this->assertTrue(strpos($message, 'Configuration Error: File doesn\'t exist') !== false);

        $this->assertEquals('Test', $this->schema->getIdentifier()->getFull());

        $this->assertEquals('test', $this->schema->getDataProviderIdentifier());

        $this->assertEquals([
            'Test' => 'Text'
        ], $this->schema->getFlatStorage());

        $this->assertEquals([], $this->schema->getParentStorage());

        $this->assertEquals([
            'Tests' => '+Test'
        ], $this->schema->getChildStorage());

    }

    public function testSchemaMerge()
    {

        $this->schema->mergeSchema(new YamlDataObjectSchema('tests/Heystack/Subsystem/Core/Test/schemas/test_schema2.yml', $this->state));

        $this->assertEquals([
            'Test' => 'Text',
            'Test2' => 'Text'
        ], $this->schema->getFlatStorage());

        $this->assertEquals([
            'Tests' => '+Test',
            'Tests2' => '+Test2'
        ], $this->schema->getChildStorage());

    }

}
