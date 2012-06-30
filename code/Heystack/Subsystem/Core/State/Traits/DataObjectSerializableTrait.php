<?php

namespace Heystack\Subsystem\Core\State\Traits;

trait DataObjectSerializableTrait
{

    public function serialize()
    {

        return serialize($this->record);

    }

    public function unserialize($data)
    {

        $this->class = get_class($this);
        $this->record = unserialize($data);

    }

}
