<?php

namespace Heystack\Subsystem\Core\State\Backends;

use Heystack\Subsystem\Core\State\BackendInterface;

class Memcache implements BackendInterface
{

    private $memcache = null;
    private $session = null;

    public function __construct(\Memcache $memcache, \Session $session = null)
    {

        $this->memcache = $memcache;

        if (!is_null($session)) {

            $this->session = $session;

        }

    }

    public function setByKey($key, $var)
    {

        $this->memcache->set($this->key($key), $var);

    }

    public function getByKey($key)
    {

        return $this->memcache->get($this->key($key));

    }

    public function removeByKey($key)
    {

        return $this->memcache->delete($this->key($key));

    }

    protected function key($key)
    {

        return !is_null($this->session) ? session_id() . '_' . $key : $key;

    }

}
