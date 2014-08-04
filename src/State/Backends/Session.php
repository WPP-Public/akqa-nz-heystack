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
     * @return void
     */
    public function setSession(\Session $session)
    {
        $this->session = $session;
    }

    /**
     * @return \Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->session->inst_getAll());
    }

    /**
     * @param string $key
     * @param mixed|null $var
     * @return void
     */
    public function setByKey($key, $var)
    {
        $this->session->inst_set($key, $var);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getByKey($key)
    {
        return $this->session->inst_get($key);
    }

    /**
     * @param string $key
     * @return void
     */
    public function removeByKey($key)
    {
        $this->session->inst_clear($key);
    }

    /**
     * @param array $exclude
     * @return void
     */
    public function removeAll(array $exclude = [])
    {
        foreach (array_keys($this->session->inst_getAll()) as $key) {
            if (!in_array($key, $exclude)) {
                $this->removeByKey($key);
            }
        }
    }
}
