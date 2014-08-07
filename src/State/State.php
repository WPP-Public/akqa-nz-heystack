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
            $this->backend->setByKey($key, \Heystack\Core\serialize($val));
        }
    }
    
    /**
     * @param string $key
     * @return mixed|null
     */
    public function getByKey($key)
    {
        return \Heystack\Core\unserialize($this->backend->getByKey($key));
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
