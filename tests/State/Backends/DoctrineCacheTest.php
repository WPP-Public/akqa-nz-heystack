<?php

namespace Heystack\Core\State\Backends;

class DoctrineCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Heystack\Core\State\Backends\DoctrineCache::__construct
     * @covers Heystack\Core\State\Backends\DoctrineCache::getKeys
     * @covers Heystack\Core\State\Backends\DoctrineCache::getByKey
     */
    public function testObjectCanBeConstructedWithValidArguments()
    {
        $keys = ['test'];

        $cacheProviderMock = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods(['fetch'])
            ->getMockForAbstractClass();

        $cacheProviderMock
            ->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($keys));
        
        $m = new DoctrineCache($cacheProviderMock);
        
        $this->assertTrue(
            is_object($m)
        );

        $this->assertAttributeEquals(
            $cacheProviderMock,
            'cacheService',
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

        $cacheProviderMock = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods(['fetch'])
            ->getMockForAbstractClass();

        $cacheProviderMock
            ->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue(null));

        $m = new DoctrineCache($cacheProviderMock, 'test');

        $this->assertTrue(
            is_object($m)
        );

        $this->assertAttributeEquals(
            $cacheProviderMock,
            'cacheService',
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
     * @covers Heystack\Core\State\Backends\DoctrineCache::__construct
     * @covers Heystack\Core\State\Backends\DoctrineCache::getKeys
     * @covers Heystack\Core\State\Backends\DoctrineCache::getByKey
     * @covers Heystack\Core\State\Backends\DoctrineCache::key
     */
    public function testCanGet()
    {
        $sid = session_id();
        $cacheProviderMock = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods(['fetch'])
            ->getMockForAbstractClass();;
        $cacheProviderMock->expects($this->at(0))
            ->method('fetch')
            ->with($sid . '_' . DoctrineCache::TRACKING_KEY)
            ->will($this->returnValue([]));

        $cacheProviderMock->expects($this->at(1))
            ->method('fetch')
            ->with($sid . '_test')
            ->will($this->returnValue('yay'));
        
        $m = new DoctrineCache($cacheProviderMock);
        $this->assertEquals(
            'yay',
            $m->getByKey('test')
        );
    }

    /**
     * @covers Heystack\Core\State\Backends\DoctrineCache::__construct
     * @covers Heystack\Core\State\Backends\DoctrineCache::getKeys
     * @covers Heystack\Core\State\Backends\DoctrineCache::getByKey
     * @covers Heystack\Core\State\Backends\DoctrineCache::setByKey
     * @covers Heystack\Core\State\Backends\DoctrineCache::addKey
     * @covers Heystack\Core\State\Backends\DoctrineCache::key
     */
    public function testCanSet()
    {
        $sid = session_id();
        $cacheProviderMock = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods(['fetch', 'save'])
            ->getMockForAbstractClass();;
        $cacheProviderMock
            ->expects($this->once())
            ->method('fetch')
            ->with($sid . '_' . DoctrineCache::TRACKING_KEY)
            ->will($this->returnValue([]));

        $cacheProviderMock
            ->expects($this->at(1))
            ->method('save')
            ->with($sid . '_test', 'test');

        $cacheProviderMock
            ->expects($this->at(2))
            ->method('save')
            ->with($sid . '_' . DoctrineCache::TRACKING_KEY, ['test' => 'test']);
        
        $m = new DoctrineCache($cacheProviderMock);
        $m->setByKey('test', 'test');
        
        $this->assertAttributeEquals(
            ['test' => 'test'],
            'keys',
            $m
        );
    }

    /**
     * @covers Heystack\Core\State\Backends\DoctrineCache::__construct
     * @covers Heystack\Core\State\Backends\DoctrineCache::getKeys
     * @covers Heystack\Core\State\Backends\DoctrineCache::getByKey
     * @covers Heystack\Core\State\Backends\DoctrineCache::removeByKey
     * @covers Heystack\Core\State\Backends\DoctrineCache::key
     */
    public function testCanRemove()
    {
        $sid = session_id();
        $cacheProviderMock = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods(['fetch', 'delete'])
            ->getMockForAbstractClass();;
        $cacheProviderMock->expects($this->once())
            ->method('fetch')
            ->with($sid . '_' . DoctrineCache::TRACKING_KEY)
            ->will($this->returnValue([]));

        $cacheProviderMock->expects($this->once())
            ->method('delete')
            ->with($sid . '_test');

        $m = new DoctrineCache($cacheProviderMock);
        $m->removeByKey('test');
    }

    /**
     * @covers Heystack\Core\State\Backends\DoctrineCache::__construct
     * @covers Heystack\Core\State\Backends\DoctrineCache::removeAll
     * @covers Heystack\Core\State\Backends\DoctrineCache::getKeys
     * @covers Heystack\Core\State\Backends\DoctrineCache::getByKey
     * @covers Heystack\Core\State\Backends\DoctrineCache::removeByKey
     * @covers Heystack\Core\State\Backends\DoctrineCache::key
     */
    public function testCanRemoveAll()
    {
        $sid = session_id();
        $cacheProviderMock = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods(['fetch', 'delete'])
            ->getMockForAbstractClass();;
        $cacheProviderMock->expects($this->once())
            ->method('fetch')
            ->with($sid . '_' . DoctrineCache::TRACKING_KEY)
            ->will($this->returnValue(['test', 'test2']));

        $cacheProviderMock->expects($this->at(1))
            ->method('delete')
            ->with($sid . '_test');

        $cacheProviderMock->expects($this->at(2))
            ->method('delete')
            ->with($sid . '_test2');

        $m = new DoctrineCache($cacheProviderMock);
        $m->removeAll();
    }

    /**
     * @covers Heystack\Core\State\Backends\DoctrineCache::__construct
     * @covers Heystack\Core\State\Backends\DoctrineCache::removeAll
     * @covers Heystack\Core\State\Backends\DoctrineCache::getKeys
     * @covers Heystack\Core\State\Backends\DoctrineCache::getByKey
     * @covers Heystack\Core\State\Backends\DoctrineCache::removeByKey
     * @covers Heystack\Core\State\Backends\DoctrineCache::key
     */
    public function testCanRemoveAllWithExcludes()
    {
        $sid = session_id();
        $cacheProviderMock = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods(['fetch', 'delete'])
            ->getMockForAbstractClass();;
        $cacheProviderMock->expects($this->once())
            ->method('fetch')
            ->with($sid . '_' . DoctrineCache::TRACKING_KEY)
            ->will($this->returnValue(['test', 'test2']));

        $cacheProviderMock->expects($this->at(1))
            ->method('delete')
            ->with($sid . '_test2');

        $m = new DoctrineCache($cacheProviderMock);
        $m->removeAll(['test']);
    }
}
