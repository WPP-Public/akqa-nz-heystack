<?php

namespace Heystack\Core\State;

/**
 * Class State
 * @package Heystack\Core\State
 */
class State
{

    /**
     * @var BackendInterface|null
     */
    private $backend = null;

    /**
     * @var
     */
    private $enabled = true;

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
        if ($this->getEnabled()) {
            $this->setByKey($key, $obj);
        }
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
        if ($this->getEnabled()) {
            $this->backend->setByKey($key, serialize($val));
        }
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
    public function removeAll(array $exclude = [])
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

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }
}
