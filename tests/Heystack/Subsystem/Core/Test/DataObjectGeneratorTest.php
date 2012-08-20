<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Generate\DataObjectGenerator;
use Heystack\Subsystem\Core\Generate\JsonDataObjectSchema;
use Heystack\Subsystem\Core\Generate\YamlDataObjectSchema;
use Heystack\Subsystem\Core\State\State;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Heystack\Subsystem\Core\Exception\ConfigurationException;

class DataObjectGeneratorTest extends \PHPUnit_Framework_TestCase
{

    protected $generator;

    protected function setUp()
    {
        $this->generator = new DataObjectGenerator(new State(new TestBackend(), new EventDispatcher()));
    }

    protected function tearDown()
    {
        $this->generator = null;
    }

    public function testAddSchema()
    {

        $this->generator->addSchema(new TestDataObjectSchema('test'));

        $this->assertTrue($this->generator->hasSchema('test'));
    }

    public function testAddByReferenceSchema()
    {

        $this->generator->addSchema(new TestDataObjectSchema('test'), true);

        $this->assertTrue($this->generator->hasSchema('test'));
    }

    public function testForceAddSchema()
    {

        $this->generator->addSchema(new TestDataObjectSchema('test'));

        $this->assertTrue($this->generator->hasSchema('test'));

        $test = new TestDataObjectSchema('test');

        $this->generator->addSchema($test, false, true);

        $this->assertEquals($test, $this->generator->getSchema('test'));
    }

    public function testMergeSchema()
    {

        $this->generator->addSchema(new TestDataObjectSchema('test'));
        $this->generator->addSchema(new TestDataObjectSchema('test'));

        $this->assertTrue($this->generator->hasSchema('test'));
    }

    public function testAddYamlSchema()
    {

        $this->generator->addYamlSchema('heystack/tests/Heystack/Subsystem/Core/Test/schemas/test_schema.yml');

        $this->assertTrue($this->generator->hasSchema('test'));
    }

    public function testAddJsonSchema()
    {

        $this->generator->addJsonSchema('heystack/tests/Heystack/Subsystem/Core/Test/schemas/test_schema.json');

        $this->assertTrue($this->generator->hasSchema('test'));
    }

    public function testGetSchema()
    {

        $schema = new TestDataObjectSchema('test');

        $this->generator->addSchema($schema);

        $this->assertEquals($schema, $this->generator->getSchema('test'));

        $this->assertEquals(false, $this->generator->getSchema('blah'));
    }

    public function testIsReference()
    {

        $this->generator->addSchema(new TestDataObjectSchema('test'));

        $this->assertEquals('test', $this->generator->isReference('+test'));
        $this->assertFalse($this->generator->isReference('test'));
        
        $message = null;

        try {
            
            $this->generator->isReference('+test1');

        } catch (ConfigurationException $e) {
            
            $message = $e->getMessage();
            
        }
        
        $this->assertNotNull($message);
    }

}
