<?php

namespace Heystack\Core\State;

/**
 * The interface that backends added to the state service needs to implement
 * @package Heystack\Core\State
 */
interface BackendInterface
{
    /**
     * @param string $key
     * @param mixed $var
     * @return void
     */
    public function setByKey($key, $var);

    /**
     * @param string $key
     * @return mixed
     */
    public function getByKey($key);

    /**
     * @param string $key
     * @return void
     */
    public function removeByKey($key);

    /**
     * @param array $exclude
     * @return void
     */
    public function removeAll(array $exclude = []);

    /**
     * @return mixed
     */
    public function getKeys();
}
