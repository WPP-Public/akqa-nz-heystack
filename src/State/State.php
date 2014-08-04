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
     * @param \Heystack\Core\State\BackendInterface $backend
     */
    public function __construct(BackendInterface $backend)
    {
        $this->backend = $backend;
    }

    /**
     * @param string $key
     * @param \Serializable $obj
     * @return void
     */
    public function setObj($key, \Serializable $obj)
    {
        if ($this->getEnabled()) {
            $this->setByKey($key, $obj);
        }
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getObj($key)
    {
        return $this->getByKey($key);
    }

    /**
     * @param string $key
     * @param mixed|null $val
     * @return void
     */
    public function setByKey($key, $val)
    {
        if ($this->getEnabled()) {
            $this->backend->setByKey($key, $this->serialize($val));
        }
    }
    
    /**
     * @param string $key
     * @return mixed|null
     */
    public function getByKey($key)
    {
        return $this->unserialize($this->backend->getByKey($key));
    }

    /**
     * Provide recursive serialization for array to attempt to avoid cases where serialization bugs occur
     * due to referenced objects
     * @param mixed|null $val
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
     * @param string|null $val
     * @return mixed|null
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
     * @param string $key
     * @return void
     */
    public function removeByKey($key)
    {
        $this->backend->removeByKey($key);
    }

    /**
     * @param array $exclude
     * @return void
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
     * @param bool $enabled
     * @return void
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
