<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Storage\Storage;
use Heystack\Subsystem\Core\Storage\StorableInterface;

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

        $storable = new TestStoraable;

        $this->assertEquals(
            array(
                'test' => $storable->getStorableData()
            ),
           $this->storage->process($storable)
        );

    }

    public function testStorageBackends()
    {
        
        $arr = array(new TestStorageBackend);

        $storage = new Storage($arr);
        
        $this->assertEquals($arr, $storage->getBackends());

    }
    
    public function testSetBackends()
    {
        
        $arr = array(new TestStorageBackend);
        
        $this->storage->setBackends();
        
        $this->assertEquals($arr, $this->storage->getBackends());
        
    }

}

class TestStoraable implements StorableInterface
{

    public function getSchemaName()
    {

        return 'test';

    }

    public function getStorableBackendIdentifiers()
    {

        return array(
            'test'
        );

    }

    public function getStorableData()
    {

        return array(
            'data' => array(
                'hello' => 'hello',
                'hello1' => 'hello',
                'hello2' => 'hello',
                'hello3' => 'hello'
            )
        );

    }

    public function getStorableIdentifier()
    {

        return 'test';

    }

}
