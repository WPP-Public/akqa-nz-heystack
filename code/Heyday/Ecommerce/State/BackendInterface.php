<?php

namespace Heyday\Ecommerce\State;

interface BackendInterface
{

    public function setByKey($key, $var);
    public function getByKey($key);

}