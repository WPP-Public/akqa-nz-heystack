<?php

namespace Heystack\Core\State\Traits;

/**
 * Class ExtraDataTrait
 * @package Heystack\Core\State\Traits
 */
trait ExtraDataTrait
{
    /**
     * @param array $data
     */
    public function setExtraData(array $data)
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }
}
