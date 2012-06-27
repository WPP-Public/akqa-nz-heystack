<?php

namespace Ecommerce\Subsystem\Core\State;

interface BackendInterface
{

    public function setByKey($key, $var);
    public function getByKey($key);
    public function removeByKey($key);

}
