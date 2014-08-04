<?php

namespace Heystack\Core\State\Traits;

/**
 * Works with ExtraDataInterface to provided the setExtraData method
 * 
 * @package Heystack\Core\State\Traits
 */
trait ExtraDataTrait
{
    /**
     * @param array $data
     * @return void
     */
    public function setExtraData(array $data)
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }
}
