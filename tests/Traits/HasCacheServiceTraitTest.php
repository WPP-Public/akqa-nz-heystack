<?php

namespace Heystack\Core\Traits;

/**
 * @package Heystack\Core\Traits
 */
class HasCacheServiceTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Heystack\Core\Traits\HasCacheServiceTrait::setCacheService
     */
    public function testCanSetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasCacheServiceTrait');
        $o->setCacheService(
            $m = $this->getMockForAbstractClass('Doctrine\Common\Cache\CacheProvider')
        );
        $this->assertAttributeEquals(
            $m,
            'cacheService',
            $o
        );
    }

    /**
     * @covers Heystack\Core\Traits\HasCacheServiceTrait::getCacheService
     * @covers Heystack\Core\Traits\HasCacheServiceTrait::setCacheService
     */
    public function testCanGetService()
    {
        $o = $this->getObjectForTrait(__NAMESPACE__ . '\HasCacheServiceTrait');
        
        $this->assertNull(
            $o->getCacheService()
        );

        $o->setCacheService(
            $m = $this->getMockForAbstractClass('Doctrine\Common\Cache\CacheProvider')
        );

        $this->assertEquals(
            $m,
            $o->getCacheService()
        );
    }
} 