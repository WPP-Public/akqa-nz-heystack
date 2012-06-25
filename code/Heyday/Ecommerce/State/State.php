<?php

use Heyday\Ecommerce\State\BackendInterface;

namespace Heyday\Ecommerce\State;

class State
{

    private $backend = null;

    public function __construct(BackendInterface $backend)
    {

        $this->backend = $backend;

    }

    public function setStateable(StateableInterface $obj)
    {

        $this->backend->setByKey($obj->getStateKey(), serialize($obj));

    }

    public function getStateable($key)
    {

        return unserialize($this->backend->getByKey($obj->getStateKey()));

    }

}