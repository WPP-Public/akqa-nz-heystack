<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Storage\Storage;

class StorageTest extends \PHPUnit_Framework_TestCase
{

    protected $storage;

    protected function setUp()
    {
        $this->storage = new Storage();
        $this->storage->addBackend(new TestStorageBackend);
    }

    protected function tearDown()
    {
        $this->storage = null;
    }

    public function testStorage()
    {

        $storable = new TestStorable;

        $this->assertEquals(
            array(
                'test' => $storable->getStorableData()
            ),
           $this->storage->process($storable)
        );

    }

    public function testStorageBackends()
    {
        
        $arr = array('test' => new TestStorageBackend);

        $storage = new Storage($arr);
        
        $this->assertEquals($arr, $storage->getBackends());

    }
    
    public function testSetBackends()
    {
        
        $arr = array('test' => new TestStorageBackend);
        
        $this->storage->setBackends($arr);
        
        $this->assertEquals($arr, $this->storage->getBackends());
        
    }
    
    public function testProcessException()
    {
        $storable = new TestStorable;

        $storage = new Storage();
        
        $message = null;
        
        try {
            
            $storage->process($storable);
            
        } catch (\Exception $e) {
            
            $message = $e->getMessage();
            
        }
        
        $this->assertNotNull($message);
    }

}

