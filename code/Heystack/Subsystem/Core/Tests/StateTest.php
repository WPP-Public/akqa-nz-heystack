<?php

namespace Heystack\Subsystem\Core\Tests;

use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\BackendInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class StateTest extends \PHPUnit_Framework_TestCase
{
    protected $state;

    protected function setUp()
    {
        $this->state = new State(new TestBackend(), new EventDispatcher());
    }

    protected function tearDown()
    {
        $this->state = null;
    }

    public function testSetGet()
    {
        $this->state->setByKey('test', 'hello');
        $this->assertEquals('hello', $this->state->getByKey('test'));
    }

    public function testRemove()
    {
        $this->state->setByKey('test', 'hello');
        $this->state->removeByKey('test');
        $this->assertEquals(null, $this->state->getByKey('test'));
    }

    public function testSetGetStatable()
    {
        $this->state->setObj('test', $obj = new TestStateable());

        $this->assertEquals($obj, $this->state->getObj('test'));

        $obj->setData(array('test2'));

        $this->assertNotEquals($obj, $this->state->getObj('test'));
    }

}

class TestBackend implements BackendInterface
{
    protected $storage = array();

    public function setByKey($key, $var)
    {

        $this->storage[$key] = $var;

    }

    public function getByKey($key)
    {

        return isset($this->storage[$key]) ? $this->storage[$key] : null;

    }

    public function removeByKey($key)
    {

        unset($this->storage[$key]);

    }
}

class TestStateable implements \Serializable
{
    protected $data = array('test');

    public function setData($data)
    {
        $this->data = $data;
    }

    public function serialize()
    {
        return serialize($this->data);
    }

    public function unserialize($data)
    {
        $this->data = unserialize($data);
    }
}
