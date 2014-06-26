<?php

namespace Heystack\Core\State;

/**
 * Provides a state service analogous to session
 * 
 * A backend should be provided that implements the backend interface
 * 
 * @package Heystack\Core\State
 */
class State
{
    /**
     * @var BackendInterface
     */
    private $backend;

    /**
     * @var bool
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
            $this->backend->setByKey($key, $this->serialize($val));
        }
    }
    
    /**
     * @param $key
     * @return mixed
     */
    public function getByKey($key)
    {
        return $this->unserialize($this->backend->getByKey($key));
    }

    /**
     * Provide recursive serialization for array to attempt to avoid cases where serialization bugs occur
     * due to referenced objects
     * @param mixed $val
     * @return string
     */
    protected function serialize($val)
    {
        if (is_array($val)) {
            $val = array_map([$this, 'serialize'], $val);
        }

        return serialize($val);
    }

    /**
     * @param string $val
     * @return mixed
     */
    protected function unserialize($val)
    {
        $val = unserialize($val);

        if (is_array($val)) {
            $val = array_map([$this, 'unserialize'], $val);
        }

        return $val;
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

    /**
     * @param $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
