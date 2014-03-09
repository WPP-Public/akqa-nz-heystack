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
    const TRACKING_KEY = 'memcache.keys';

    private $memcache = null;
    private $session = null;
    private $keys = false;
    private $prefix = '';

    public function __construct(\Memcache $memcache, \Session $session = null, $prefix = null)
    {
        $this->memcache = $memcache;

        if (!is_null($session)) {

            $this->session = $session;

        }

        if (!is_null($prefix)) {

            $this->prefix = $prefix;

        }

        //Sets up keys
        $this->getKeys();
    }

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

    public function addKey($key)
    {
        $this->keys[$key] = $key;

        $this->memcache->set($this->key(self::TRACKING_KEY), $this->keys);
    }

    public function setByKey($key, $var)
    {
        $this->memcache->set($this->key($key), $var);

        $this->addKey($key);
    }

    public function getByKey($key)
    {
        return $this->memcache->get($this->key($key));
    }

    public function removeByKey($key)
    {
        return $this->memcache->delete($this->key($key));
    }

    public function removeAll(array $exclude = [])
    {
        if (is_array($this->keys)) {

            foreach (array_diff($this->keys, $exclude) as $key) {

                $this->removeByKey($key);

            }

        }
    }

    protected function key($key)
    {
        return !is_null($this->session) ? session_id() . '_' . $key : $this->prefix . $key;
    }
}
