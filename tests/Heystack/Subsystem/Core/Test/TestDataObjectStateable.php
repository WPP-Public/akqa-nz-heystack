<?php

namespace Heystack\Core\Test;

use Heystack\Core\State\Traits\DataObjectSerializableTrait;

class TestDataObjectStateable extends \DataObject implements \Serializable
{

    use DataObjectSerializableTrait;

}
