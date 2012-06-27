<?php

namespace Ecommerce\Subsystem\Core\State;

use Ecommerce\Subsystem\Core\State\BackendInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class State
{

    private $backend = null;
    private $dispatcher = null;

    public function __construct(BackendInterface $backend, EventDispatcher $dispatcher)
    {

        $this->backend = $backend;
        $this->dispatcher = $dispatcher;

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

        $this->dispatcher->dispatch(Events::STATE_SET);

    }

    public function getByKey($key)
    {

        return unserialize($this->backend->getByKey($key));

    }

    public function removeByKey($key)
    {

        $this->backend->removeByKey($key);

        $this->dispatcher->dispatch(Events::STATE_REMOVE);

    }

}