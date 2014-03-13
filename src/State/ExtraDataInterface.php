<?php

namespace Heystack\Core\State;

/**
 * Allows the storing of extra data when used in combination with ExtraDataTrait
 * 
 * When using DataObjectSerializableTrait for serialization implementing this interface
 * allows you to store extra data in state
 * 
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
