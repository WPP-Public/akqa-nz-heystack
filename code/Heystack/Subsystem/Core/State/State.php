<?php

namespace Heystack\Subsystem\Core\State;

use Heystack\Subsystem\Core\State\BackendInterface;

class State
{

    private $backend = null;

    public function __construct(BackendInterface $backend)
    {

        $this->backend = $backend;

    }

    public function setObj($key, \Serializable $obj)
    {

        $this->setByKey($key, $obj);

    }

    public function getObj($key)
    {

        return $this->getByKey($key);

    }

    public function setByKey($key, $val)
    {

        $this->backend->setByKey($key, serialize($val));

    }

    public function getByKey($key)
    {

        return unserialize($this->backend->getByKey($key));

    }

    public function removeByKey($key)
    {

        $this->backend->removeByKey($key);

    }

    public function removeAll(array $exclude = array())
    {

        $this->backend->removeAll($exclude);

    }
    
    public function getKeys()
    {
        
        return $this->backend->getKeys();
        
    }

}
