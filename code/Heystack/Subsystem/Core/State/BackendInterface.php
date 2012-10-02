<?php

namespace Heystack\Subsystem\Core\State;

interface BackendInterface
{

    public function setByKey($key, $var);
    public function getByKey($key);
    public function removeByKey($key);
    public function removeAll(array $exclude = array());
    public function getKeys();

}
