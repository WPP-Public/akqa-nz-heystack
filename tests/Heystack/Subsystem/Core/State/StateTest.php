<?php

namespace Heystack\Subsystem\Core\State;

class StateTest extends \PHPUnit_Framework_TestCase
{
    protected $state;
    protected $backendMock;

    protected function setUp()
    {
        $this->backendMock = $this->getMock('Heystack\Subsystem\Core\State\BackendInterface');
        $this->state = new State($this->backendMock);
    }

    protected function tearDown()
    {
        $this->state = null;
        $this->backendMock = null;
    }

    public function testSet()
    {
        $this->backendMock->expects($this->once())
            ->method('setByKey')
            ->with($this->equalTo('test'), $this->equalTo(serialize('hello')));

        $this->state->setByKey('test', 'hello');
    }
    public function testGet()
    {
        $this->backendMock->expects($this->once())
            ->method('getByKey')
            ->with($this->equalTo('test'));

        $this->state->getByKey('test');
    }

    public function testRemove()
    {
        $this->backendMock->expects($this->once())
            ->method('removeByKey')
            ->with($this->equalTo('test'));

        $this->state->removeByKey('test');
    }

    public function testRemoveAll()
    {
        $this->backendMock->expects($this->once())
            ->method('removeAll')
            ->with($this->equalTo(array()));

        $this->state->removeAll();
    }

    public function testRemoveAllWithExclude()
    {
        $this->backendMock->expects($this->once())
            ->method('removeAll')
            ->with($this->equalTo(array('test')));

        $this->state->removeAll(array('test'));
    }

    public function testSetStatable()
    {
        $stub = $this->getMock('Serializable');

        $this->backendMock->expects($this->once())
            ->method('setByKey')
            ->with($this->equalTo('test'), $this->equalTo(serialize($stub)));

        $this->state->setObj('test', $stub);
    }

    public function testGetStatable()
    {
        $this->backendMock->expects($this->once())
            ->method('getByKey')
            ->with($this->equalTo('test'));

        $this->state->getObj('test');
    }

    public function testGetKeys()
    {
        $this->backendMock->expects($this->once())
            ->method('getKeys');
        $this->state->getKeys();
    }
}
