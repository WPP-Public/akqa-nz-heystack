<?php

namespace Heystack\Core\Interfaces;

/**
 * Class HasDataInterface
 * @package Heystack\Core\Interfaces
 */
interface HasDataInterface
{
    /**
     * @param mixed $data
     * @return void
     */
    public function setData($data);

    /**
     * @return mixed
     */
    public function getData();
}
