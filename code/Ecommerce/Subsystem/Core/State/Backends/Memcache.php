<?php

namespace Ecommerce\Subsystem\Core\State\Backends;

use Ecommerce\Subsystem\Core\State\BackendInterface;

class Memcache implements BackendInterface
{

    private $memcache = null;

    public function __construct(\Memcache $memcache)
    {

        $this->memcache = $memcache;

    }

    public function setByKey($key, $var)
    {

        $this->memcache->set($key, $var);

    }

    public function getByKey($key)
    {

        return $this->memcache->get($key);

    }

    public function removeByKey($key)
    {

        return $this->memcache->delete($key);

    }

}
