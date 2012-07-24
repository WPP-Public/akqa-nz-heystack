<?php

namespace Heystack\Subsystem\Core\State\Backends;

use Heystack\Subsystem\Core\State\BackendInterface;

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

    public function removeAll()
    {

        $this->session->inst_clearAll();

    }

    protected function save()
    {

        $this->session->inst_save();

    }

}