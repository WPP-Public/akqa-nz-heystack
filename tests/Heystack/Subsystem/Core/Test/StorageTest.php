<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Storage\Storage;

use Heystack\Subsystem\Core\Storage\Event;

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

        $results = $this->storage->process($storable);

        $this->assertEquals(
            array(
                'test' => $storable->getStorableData()
            ),
            $results
        );

        $storable2 = new TestStorable;

        $storable2->setParentReference($results['test']);

        $this->assertEquals($results['test'], $storable2->getParentReference());

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

    public function testEvent()
    {

        $event = new Event('test');

        $this->assertEquals('test', $event->getParentReference());

    }

}
