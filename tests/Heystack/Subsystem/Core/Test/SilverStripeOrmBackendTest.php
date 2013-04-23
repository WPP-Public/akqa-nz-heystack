<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\State\State;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Heystack\Subsystem\Core\Generate\DataObjectGenerator;

use Heystack\Subsystem\Core\Exception\ConfigurationException;

use Heystack\Subsystem\Core\Storage\Backends\SilverStripeOrm\Backend;

class SilverStripeOrmBackendTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $this->state = $this->getMockBuilder('Heystack\Subsystem\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();
        $this->backend = new Backend(
            new EventDispatcher(),
            new DataObjectGenerator(
                $this->state
            )
        );
    }

    protected function tearDown()
    {
        $this->backend = null;
    }
    
    public function testIdentifier()
    {
        
        $this->assertEquals(Backend::IDENTIFIER, $this->backend->getIdentifier()->getPrimary());
        
    }
    
    public function testAddHasDataProvider()
    {
        
        $this->backend->addDataProvider(new TestStorable);
        
        $this->assertTrue($this->backend->hasDataProvider('test'));
        
    }
    
    public function testWrite()
    {
        
        $object = new TestDataObjectStorable(array(
            'Test' => 'Test'
        ));
        
        $this->assertEquals('Configuration Error: Couldn\'t find data provider for identifier: ' . $object->getStorableIdentifier(), $this->tryCatchWriteHelper($object));
        
        $this->backend->addDataProvider($object);
        
        $this->assertEquals('Configuration Error: No schema found for identifier: ' . strtolower($object->getSchemaName()), $this->tryCatchWriteHelper($object));

    }
    
    protected function tryCatchWriteHelper($object)
    {
        
        try {
            
            $this->backend->write($object);
            
        } catch (ConfigurationException $e) {
            
            return $e->getMessage();

        }
        
        return null;
        
    }
    
}