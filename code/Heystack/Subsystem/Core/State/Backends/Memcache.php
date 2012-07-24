<?php

namespace Heystack\Subsystem\Core\State\Backends;

use Heystack\Subsystem\Core\State\BackendInterface;

class Memcache implements BackendInterface
{

    const TRACKING_KEY = 'memcache.keys';

    private $memcache = null;
    private $session = null;
    private $keys = false;

    public function __construct(\Memcache $memcache, \Session $session = null)
    {

        $this->memcache = $memcache;

        if (!is_null($session)) {

            $this->session = $session;

        }

        //Sets up keys
        $this->getKeys();

    }

    public function getKeys()
    {

        if (!is_array($this->keys)) {

            $this->keys = $this->getByKey(self::TRACKING_KEY);

            if (!is_array($this->keys)) {

                $this->keys = array();

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

    public function removeAll()
    {

        if (is_array($this->keys)) {

            foreach ($this->keys as $key) {

                $this->removeByKey($key);

            }

        }

    }

    protected function key($key)
    {

        return !is_null($this->session) ? session_id() . '_' . $key : $key;

    }

}
