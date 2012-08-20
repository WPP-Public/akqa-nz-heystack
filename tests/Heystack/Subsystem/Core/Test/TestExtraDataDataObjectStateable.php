<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\State\Traits\DataObjectSerializableTrait;
use Heystack\Subsystem\Core\State\Traits\ExtraDataTrait;
use Heystack\Subsystem\Core\State\ExtraDataInterface;

class TestExtraDataDataObjectStateable extends \DataObject
    implements ExtraDataInterface, \Serializable
{

    use DataObjectSerializableTrait;
    use ExtraDataTrait;

    protected $extraData = array();

    public function setExtraData(array $extraData)
    {
        $this->extraData = $extraData;
    }

    public function getExtraData()
    {

        return $this->extraData;
    }

}