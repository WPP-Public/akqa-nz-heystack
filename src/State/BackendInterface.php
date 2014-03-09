<?php

namespace Heystack\Core\State;

/**
 * Class BackendInterface
 * @package Heystack\Core\State
 */
interface BackendInterface
{
    /**
     * @param $key
     * @param $var
     * @return void
     */
    public function setByKey($key, $var);

    /**
     * @param $key
     * @return mixed
     */
    public function getByKey($key);

    /**
     * @param $key
     * @return void
     */
    public function removeByKey($key);

    /**
     * @param  array $exclude
     * @return void
     */
    public function removeAll(array $exclude = []);

    /**
     * @return mixed
     */
    public function getKeys();
}
