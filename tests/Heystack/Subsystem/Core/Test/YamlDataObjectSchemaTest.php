<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Generate\YamlDataObjectSchema;

use Heystack\Subsystem\Core\State\State;

use Symfony\Component\EventDispatcher\EventDispatcher;

class YamlDataObjectSchemaTest extends \PHPUnit_Framework_TestCase
{

    protected $state;
    protected $schema;

    protected function setUp()
    {

        $this->state = new State(new TestBackend(), new EventDispatcher());

        $this->schema = new YamlDataObjectSchema('/heystack/tests/Heystack/Subsystem/Core/Test/schemas/test_schema.yml', $this->state);

    }

    protected function tearDown()
    {

        $this->state = null;
        $this->schema = null;

    }

    public function testSchema()
    {

        try {

            new YamlDataObjectSchema('fake_file.yml', $this->state);

        } catch (\Exception $e) {

            $this->assertEquals('File doesn\'t exist', $e->getMessage());

        }

        $this->assertEquals('Test', $this->schema->getIdentifier());

        $this->assertEquals('test', $this->schema->getDataProviderIdentifier());

        $this->assertEquals(array(
            'Test' => 'Text'
        ), $this->schema->getFlatStorage());

        $this->assertEquals(null, $this->schema->getRelatedStorage());

        $this->assertEquals(null, $this->schema->getParentStorage());

        $this->assertEquals(array(), $this->schema->getChildStorage());

    }

    public function testSchemaMerge()
    {

        $this->schema->mergeSchema(new YamlDataObjectSchema('/heystack/tests/Heystack/Subsystem/Core/Test/schemas/test_schema2.yml', $this->state));

        $this->assertEquals(array(
            'Test' => 'Text',
            'Test2' => 'Text'
        ), $this->schema->getFlatStorage());

    }

}
