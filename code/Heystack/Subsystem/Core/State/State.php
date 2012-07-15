<?php

namespace Heystack\Subsystem\Core\State;

use Heystack\Subsystem\Core\State\BackendInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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

    public function getByLikeKey($key) //TODO: either this needs to be added to the interface, or removed.
    {
        $objects = array();

        foreach ($this->backend->getByLikeKey($key) as $object) {

            $objects[] = unserialize($object);

        }

        return $objects;
    }

}
