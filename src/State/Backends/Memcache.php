<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Backends namespace
 */
namespace Heystack\Core\State\Backends;

use Heystack\Core\State\BackendInterface;

/**
 * Memcache storage for backend
 */
class Memcache implements BackendInterface
{
    /**
     *
     */
    const TRACKING_KEY = 'memcache.keys';

    /**
     * @var \Memcache
     */
    private $memcache;
    /**
     * @var bool
     */
    private $session = false;
    /**
     * @var bool
     */
    private $keys = false;
    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @param \Memcache $memcache
     * @param bool $session
     * @param null $prefix
     */
    public function __construct(\Memcache $memcache, $session = false, $prefix = null)
    {
        $this->memcache = $memcache;
        $this->session = $session;

        if (!is_null($prefix)) {
            $this->prefix = $prefix;
        }

        //Sets up keys
        $this->getKeys();
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        if (!is_array($this->keys)) {
            $this->keys = $this->getByKey(self::TRACKING_KEY);

            if (!is_array($this->keys)) {
                $this->keys = [];
            }
        }

        return $this->keys;
    }

    /**
     * @param $key
     */
    public function addKey($key)
    {
        $this->keys[$key] = $key;

        $this->memcache->set($this->key(self::TRACKING_KEY), $this->keys);
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
     * @return string
     */
    protected function key($key)
    {
        if ($this->session) {
            if (!isset($_SESSION)) {
                \Session::start();
            }
            return session_id() . '_' . $key;
        } else {
            return $this->prefix . $key;
        }
    }
}
