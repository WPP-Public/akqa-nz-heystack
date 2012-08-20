<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\State\State;
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

    public function testRemoveAll()
    {
        $this->state->setByKey('test', 'hello');
        $this->state->removeAll();
        $this->assertEquals(array(), $this->state->getKeys());
    }

    public function testSetGetStatable()
    {
        $this->state->setObj('test', $obj = new TestStateable());

        $this->assertEquals($obj, $this->state->getObj('test'));

        $obj->setData(array('test2'));

        $this->assertNotEquals($obj, $this->state->getObj('test'));
    }

    public function testGetKeys()
    {

        $this->assertEquals(array(), $this->state->getKeys());

        $this->state->setByKey('test', 'Yay');

        $this->assertEquals(array('test'), $this->state->getKeys());

        $this->state->setByKey('test2', 'Yay');

        $this->assertEquals(array('test', 'test2'), $this->state->getKeys());

    }
    
    public function testDataObjectStateable()
    {
        
        $do = new TestDataObjectStateable(array(
            'Hello' => 'test',
            'Hello2' => 'test'
        ));
        
        $this->state->setObj('test', $do);
        
        $this->assertEquals($do->toMap(), $this->state->getObj('test')->toMap());
        
    }
    
    public function testExtraDataDataObjectStateable()
    {
        
        $do = new TestExtraDataDataObjectStateable(array(
            'Hello' => 'test',
            'Hello2' => 'test'
        ));
        
        $do->setExtraData(array(
            'ExtraData' => 'test',
            'ExtraData2' => 'test'
        ));
        
        $this->state->setObj('test', $do);
        
        $this->assertEquals($do->getExtraData(), $this->state->getObj('test')->getExtraData());
        
    }

}
