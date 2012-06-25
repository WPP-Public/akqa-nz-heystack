<?php


use Heyday\Ecommerce\State\BackendInterface;

namespace Heyday\Ecommerce\State\Backends;

class Session implements BackendInterface
{

    public function setByKey($key, $var)
    {

        $_SESSION[$key] = $var;

    }

    public function gettByKey($key)
    {

        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;

    }

}