<?php

namespace Heystack\Core\State\Traits;

trait ExtraDataTrait
{

    public function setExtraData(array $data)
    {
        foreach ($data as $key => $val) {
            $this->$key = $val;
        }
    }

}
