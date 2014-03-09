<?php

namespace Heystack\Core\State;

/**
 * Interface ExtraDataInterface
 * @package Heystack\Core\State
 */
interface ExtraDataInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function setExtraData(array $data);

    /**
     * @return mixed
     */
    public function getExtraData();
}
