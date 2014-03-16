<?php

namespace Heystack\Core\State\Backends;

/**
 * @requires extension memcache
 */
class MemcacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Heystack\Core\State\Backends\Memcache::__construct
     * @covers Heystack\Core\State\Backends\Memcache::getKeys
     * @covers Heystack\Core\State\Backends\Memcache::getByKey
     */
    public function testObjectCanBeConstructedWithValidArguments()
    {
        $keys = ['test'];

        $memcacheMock = $this->getMock('Memcache');
        $memcacheMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue($keys));
        
        $m = new Memcache($memcacheMock);
        
        $this->assertTrue(
            is_object($m)
        );

        $this->assertAttributeEquals(
            $memcacheMock,
            'memcache',
            $m
        );

        $this->assertAttributeEquals(
            '',
            'prefix',
            $m
        );

        $this->assertAttributeEquals(
            $keys,
            'keys',
            $m
        );

        $memcacheMock = $this->getMock('Memcache');
        $memcacheMock->expects($this->once())
            ->method('get')
            ->will($this->returnValue(null));

        $m = new Memcache($memcacheMock, 'test');

        $this->assertTrue(
            is_object($m)
        );

        $this->assertAttributeEquals(
            $memcacheMock,
            'memcache',
            $m
        );
        
        $this->assertAttributeEquals(
            'test',
            'prefix',
            $m
        );

        $this->assertAttributeEquals(
            [],
            'keys',
            $m
        );
    }

    /**
     * @covers Heystack\Core\State\Backends\Memcache::__construct
     * @covers Heystack\Core\State\Backends\Memcache::getKeys
     * @covers Heystack\Core\State\Backends\Memcache::getByKey
     * @covers Heystack\Core\State\Backends\Memcache::key
     */
    public function testCanGet()
    {
        $sid = session_id();
        $memcacheMock = $this->getMock('Memcache');
        $memcacheMock->expects($this->at(0))
            ->method('get')
            ->with($sid . '_' . Memcache::TRACKING_KEY)
            ->will($this->returnValue([]));
        
        $memcacheMock->expects($this->at(1))
            ->method('get')
            ->with($sid . '_test')
            ->will($this->returnValue('yay'));
        
        $m = new Memcache($memcacheMock);
        $this->assertEquals(
            'yay',
            $m->getByKey('test')
        );
    }

    /**
     * @covers Heystack\Core\State\Backends\Memcache::__construct
     * @covers Heystack\Core\State\Backends\Memcache::getKeys
     * @covers Heystack\Core\State\Backends\Memcache::getByKey
     * @covers Heystack\Core\State\Backends\Memcache::setByKey
     * @covers Heystack\Core\State\Backends\Memcache::addKey
     * @covers Heystack\Core\State\Backends\Memcache::key
     */
    public function testCanSet()
    {
        $sid = session_id();
        $memcacheMock = $this->getMock('Memcache');
        $memcacheMock
            ->expects($this->once())
            ->method('get')
            ->with($sid . '_' . Memcache::TRACKING_KEY)
            ->will($this->returnValue([]));

        $memcacheMock
            ->expects($this->at(1))
            ->method('set')
            ->with($sid . '_test', 'test');

        $memcacheMock
            ->expects($this->at(2))
            ->method('set')
            ->with($sid . '_' . Memcache::TRACKING_KEY, ['test' => 'test']);
        
        $m = new Memcache($memcacheMock);
        $m->setByKey('test', 'test');
        
        $this->assertAttributeEquals(
            ['test' => 'test'],
            'keys',
            $m
        );
    }

    /**
     * @covers Heystack\Core\State\Backends\Memcache::__construct
     * @covers Heystack\Core\State\Backends\Memcache::getKeys
     * @covers Heystack\Core\State\Backends\Memcache::getByKey
     * @covers Heystack\Core\State\Backends\Memcache::removeByKey
     * @covers Heystack\Core\State\Backends\Memcache::key
     */
    public function testCanRemove()
    {
        $sid = session_id();
        $memcacheMock = $this->getMock('Memcache');
        $memcacheMock->expects($this->once())
            ->method('get')
            ->with($sid . '_' . Memcache::TRACKING_KEY)
            ->will($this->returnValue([]));

        $memcacheMock->expects($this->once())
            ->method('delete')
            ->with($sid . '_test');

        $m = new Memcache($memcacheMock);
        $m->removeByKey('test');
    }

    /**
     * @covers Heystack\Core\State\Backends\Memcache::__construct
     * @covers Heystack\Core\State\Backends\Memcache::removeAll
     * @covers Heystack\Core\State\Backends\Memcache::getKeys
     * @covers Heystack\Core\State\Backends\Memcache::getByKey
     * @covers Heystack\Core\State\Backends\Memcache::removeByKey
     * @covers Heystack\Core\State\Backends\Memcache::key
     */
    public function testCanRemoveAll()
    {
        $sid = session_id();
        $memcacheMock = $this->getMock('Memcache');
        $memcacheMock->expects($this->once())
            ->method('get')
            ->with($sid . '_' . Memcache::TRACKING_KEY)
            ->will($this->returnValue(['test', 'test2']));

        $memcacheMock->expects($this->at(1))
            ->method('delete')
            ->with($sid . '_test');

        $memcacheMock->expects($this->at(2))
            ->method('delete')
            ->with($sid . '_test2');

        $m = new Memcache($memcacheMock);
        $m->removeAll();
    }

    /**
     * @covers Heystack\Core\State\Backends\Memcache::__construct
     * @covers Heystack\Core\State\Backends\Memcache::removeAll
     * @covers Heystack\Core\State\Backends\Memcache::getKeys
     * @covers Heystack\Core\State\Backends\Memcache::getByKey
     * @covers Heystack\Core\State\Backends\Memcache::removeByKey
     * @covers Heystack\Core\State\Backends\Memcache::key
     */
    public function testCanRemoveAllWithExcludes()
    {
        $sid = session_id();
        $memcacheMock = $this->getMock('Memcache');
        $memcacheMock->expects($this->once())
            ->method('get')
            ->with($sid . '_' . Memcache::TRACKING_KEY)
            ->will($this->returnValue(['test', 'test2']));

        $memcacheMock->expects($this->at(1))
            ->method('delete')
            ->with($sid . '_test2');

        $m = new Memcache($memcacheMock);
        $m->removeAll(['test']);
    }
}
