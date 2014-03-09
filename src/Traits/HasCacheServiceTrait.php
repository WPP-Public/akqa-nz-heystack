<?php

namespace Heystack\Core\Traits;

use Doctrine\Common\Cache\CacheProvider;

/**
 * Class HasCacheServiceTrait
 * @package Heystack\Core\Traits
 */
trait HasCacheServiceTrait
{
    /**
     * @var \Doctrine\Common\Cache\CacheProvider
     */
    protected $cacheService;

    /**
     * @param \Doctrine\Common\Cache\CacheProvider $cacheService
     */
    public function setCacheService(CacheProvider $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    public function getCacheService()
    {
        return $this->cacheService;
    }
} 