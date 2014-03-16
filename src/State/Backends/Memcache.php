<?php

namespace Heystack\Core\State\Backends;

use Heystack\Core\State\BackendInterface;

/**
 * A Memcache based implementation for state
 * 
 * @package Heystack\Core\State\Backends
 */
class Memcache implements BackendInterface
{
    /**
     * The key to use for tracking keys added to memcache by this backend
     */
    const TRACKING_KEY = 'memcache.keys';

    /**
     * @var \Memcache
     */
    private $memcache;
    
    /**
     * @var array|null
     */
    private $keys;

    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @param \Memcache $memcache
     * @param null $prefix
     */
    public function __construct(\Memcache $memcache, $prefix = null)
    {
        $this->memcache = $memcache;

        if (!is_null($prefix)) {
            $this->prefix = $prefix;
        }

        // Sets up keys
        $this->getKeys();
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        if (!$this->keys) {
            $this->keys = $this->getByKey(self::TRACKING_KEY);

            if (!is_array($this->keys)) {
                $this->keys = [];
            }
        }

        return $this->keys;
    }

    /**
     * @param $key
     * @param $var
     */
    public function setByKey($key, $var)
    {
        $this->memcache->set($this->key($key), $var);

        $this->addKey($key);
    }

    /**
     * @param $key
     * @return array|string
     */
    public function getByKey($key)
    {
        return $this->memcache->get($this->key($key));
    }

    /**
     * @param $key
     * @return bool
     */
    public function removeByKey($key)
    {
        return $this->memcache->delete($this->key($key));
    }

    /**
     * @param array $exclude
     */
    public function removeAll(array $exclude = [])
    {
        if (is_array($this->keys)) {
            foreach (array_diff($this->keys, $exclude) as $key) {
                $this->removeByKey($key);
            }
        }
    }

    /**
     * @param $key
     */
    protected function addKey($key)
    {
        $this->keys[$key] = $key;

        $this->memcache->set($this->key(self::TRACKING_KEY), $this->keys);
    }

    /**
     * @param $key
     * @return string
     */
    protected function key($key)
    {
        if (!isset($_SESSION)) {
            \Session::start();
        }
        return sprintf(
            "%s_%s%s",
            session_id(),
            $this->prefix,
            $key
        );
    }
}
