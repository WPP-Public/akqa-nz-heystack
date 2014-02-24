<?php

namespace Heystack\Subsystem\Core\Interfaces;

/**
 * Class HasDataInterface
 * @package Heystack\Subsystem\Core\Interfaces
 */
interface HasDataInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function setData($data);
    /**
     * @return mixed
     */
    public function getData();
}
