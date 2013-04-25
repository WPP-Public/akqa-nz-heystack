<?php

namespace Heystack\Subsystem\Core\State\Traits;

use Heystack\Subsystem\Core\State\ExtraDataInterface;

trait DataObjectSerializableTrait
{

    public function serialize()
    {
        if ($this instanceof ExtraDataInterface) {
            return serialize(array($this->record,$this->getExtraData()));

        }

        return serialize($this->record);

    }

    public function unserialize($data)
    {

        $this->class = get_class($this);

        if ($this instanceof ExtraDataInterface) {

            $unserialized = unserialize($data);
            $this->record = $unserialized[0];
            $this->setExtraData($unserialized[1]);

        } else {

            $this->record = unserialize($data);

        }

    }

}
