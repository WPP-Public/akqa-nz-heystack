<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\State\Traits\DataObjectSerializableTrait;

class TestDataObjectStateable extends \DataObject implements \Serializable
{
    
    use DataObjectSerializableTrait;
    
}