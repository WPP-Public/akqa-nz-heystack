<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\State\State;

use Symfony\Component\EventDispatcher\EventDispatcher;

use Heystack\Subsystem\Core\Exception\ConfigurationException;

class FileDataObjectSchemaTest extends \PHPUnit_Framework_TestCase
{

    protected $state;
    protected $schema;

    protected function setUp()
    {

        $this->state = new State(new TestBackend(), new EventDispatcher());

    }

    protected function tearDown()
    {

        $this->state = null;

    }

    public function testSchemaExceptions()
    {
        
        $this->assertEquals('Configuration Error: Your config is empty', $this->helperTryCatch(false));
        $this->assertEquals('Configuration Error: Identifier missing', $this->helperTryCatch(array()));
        $this->assertEquals('Configuration Error: Flat config missing', $this->helperTryCatch(array(
            'id' => 'test'
        )));
        $this->assertEquals('Configuration Error: Related config missing', $this->helperTryCatch(array(
            'id' => 'test',
            'flat' => array(
                'Test' => 'Text'
            )
        )));
        $this->assertNull($this->helperTryCatch(array(
            'id' => 'test',
            'flat' => array(
                'Test' => 'Text'
            ),
            'related' => array(
                'Something'
            )
        )));

    }
    
    public function helperTryCatch($config)
    {
        
        try {
            
            new TestFileDataObjectSchema(serialize($config), $this->state);
            
        } catch (ConfigurationException $e) {
            
            return $e->getMessage();

        }
        
        return null;
        
    }

}
