<?php

namespace Heystack\Subsystem\Core\State;

use Heystack\Subsystem\Core\State\BackendInterface;

/**
 * Class State
 * @package Heystack\Subsystem\Core\State
 */
class State
{

    /**
     * @var BackendInterface|null
     */
    private $backend = null;
    /**
     * @param BackendInterface $backend
     */
    public function __construct(BackendInterface $backend)
    {
        $this->backend = $backend;
    }
    /**
     * @param               $key
     * @param \Serializable $obj
     */
    public function setObj($key, \Serializable $obj)
    {
        $this->setByKey($key, $obj);
    }
    /**
     * @param $key
     * @return mixed
     */
    public function getObj($key)
    {
        return $this->getByKey($key);
    }
    /**
     * @param $key
     * @param $val
     */
    public function setByKey($key, $val)
    {
        $this->backend->setByKey($key, serialize($val));
    }
    /**
     * @param $key
     * @return mixed
     */
    public function getByKey($key)
    {
        return unserialize($this->backend->getByKey($key));
    }
    /**
     * @param $key
     */
    public function removeByKey($key)
    {
        $this->backend->removeByKey($key);
    }
    /**
     * @param array $exclude
     */
    public function removeAll(array $exclude = array())
    {
        $this->backend->removeAll($exclude);
    }
    /**
     * @return mixed
     */
    public function getKeys()
    {
        return $this->backend->getKeys();
    }
}
