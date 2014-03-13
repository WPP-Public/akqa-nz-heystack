<?php

namespace Heystack\Core\State\Backends;

use Heystack\Core\State\BackendInterface;

/**
 * Session based implementation for the state system
 * @package Heystack\Core\State\Backends
 */
class Session implements BackendInterface
{

    /**
     * @var \Session
     */
    private $session;

    /**
     * @param \Session $session
     */
    public function setSession(\Session $session)
    {
        $this->session = $session;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->session->inst_getAll());
    }

    /**
     * @param $key
     * @param $var
     */
    public function setByKey($key, $var)
    {
        $this->session->inst_set($key, $var);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getByKey($key)
    {
        return $this->session->inst_get($key);
    }

    /**
     * @param $key
     */
    public function removeByKey($key)
    {
        $this->session->inst_clear($key);
    }

    /**
     * @param array $exclude
     */
    public function removeAll(array $exclude = [])
    {
        foreach (array_keys($this->session->inst_getAll()) as $key) {
            if (!in_array($key, $exclude)) {
                $this->removeByKey($key);
            }
        }
    }

    /**
     * Saves the session into the $_SESSION
     */
    protected function save()
    {
        $this->session->inst_save();
    }
}
