<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Generate\DataObjectGenerator;

use Heystack\Subsystem\Core\Generate\JsonDataObjectSchema;
use Heystack\Subsystem\Core\Generate\YamlDataObjectSchema;

use Heystack\Subsystem\Core\State\State;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
        
        $this->generator->addSchema(new TestDataObjectSchema());
        
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
        
        $schema = new TestDataObjectSchema;
        
        $this->generator->addSchema($schema);
        
        $this->assertEquals($schema, $this->generator->getSchema('test'));
        
    }

}
