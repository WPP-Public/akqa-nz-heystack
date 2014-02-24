<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Backends namespace
 */
namespace Heystack\Core\State\Backends;

use Heystack\Core\State\BackendInterface;

/**
 * Session storage for backend
 */
class Session implements BackendInterface
{

    private $session = null;

    public function __construct(\Session $session)
    {

        $this->session = $session;

        if (!isset($_SESSION)) {

            session_start();

        }

        if (is_array($_SESSION)) {

            foreach ($_SESSION as $key => $val) {

                $this->session->inst_set($key, $val);

            }

        }

    }

    public function getKeys()
    {
        return array_keys($this->session->inst_getAll());

    }

    public function setByKey($key, $var)
    {

        $this->session->inst_set($key, $var);
        $this->save();

    }

    public function getByKey($key)
    {
        return $this->session->inst_get($key);

    }

    public function removeByKey($key)
    {

        $this->session->inst_clear($key);
        $this->save();

    }

    public function removeAll(array $exclude = [])
    {

        foreach (array_keys($this->session->inst_getAll()) as $key) {

            if (!in_array($key, $exclude)) {

                $this->removeByKey($key);

            }

        }

    }

    protected function save()
    {

        $this->session->inst_save();

    }
}
