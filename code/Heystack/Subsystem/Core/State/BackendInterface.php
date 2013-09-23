<?php

namespace Heystack\Subsystem\Core\State;

/**
 * Class BackendInterface
 * @package Heystack\Subsystem\Core\State
 */
interface BackendInterface
{
    /**
     * @param $key
     * @param $var
     * @return mixed
     */
    public function setByKey($key, $var);
    /**
     * @param $key
     * @return mixed
     */
    public function getByKey($key);
    /**
     * @param $key
     * @return mixed
     */
    public function removeByKey($key);
    /**
     * @param  array $exclude
     * @return mixed
     */
    public function removeAll(array $exclude = []);
    /**
     * @return mixed
     */
    public function getKeys();
}
