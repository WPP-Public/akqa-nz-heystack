<?php

namespace Heystack\Subsystem\Core\Tests;

use Heystack\Subsystem\Core\Generate\YamlDataObjectGeneratorSchema;

use Heystack\Subsystem\Core\State\StateableInterface;
use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\BackendInterface;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Yaml\Yaml;

class YamlDataObjectGeneratorSchemaTest extends \PHPUnit_Framework_TestCase
{

    protected $state;
    protected $schema;

    protected function setUp()
    {

        $this->state = new State(new TestBackend(), new EventDispatcher());

        $this->schema = new YamlDataObjectGeneratorSchema('/heystack/code/Heystack/Subsystem/Core/Tests/schemas/test_schema.yml', $this->state);

    }

    protected function tearDown()
    {

        $this->state = null;
        $this->schema = null;

    }

    public function testSchema()
    {

        try {

            new YamlDataObjectGeneratorSchema('fake_file.yml', $this->state);

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

        $this->schema->mergeSchema(new YamlDataObjectGeneratorSchema('/heystack/code/Heystack/Subsystem/Core/Tests/schemas/test_schema2.yml', $this->state));
        
        $this->assertEquals(array(
            'Test' => 'Text',
            'Test2' => 'Text'
        ), $this->schema->getFlatStorage());
        
    }

}
