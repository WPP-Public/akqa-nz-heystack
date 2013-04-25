<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Storage\StorableInterface;
use Heystack\Subsystem\Core\Storage\Traits\ParentReferenceTrait;

class TestStorable implements StorableInterface
{

    use ParentReferenceTrait;

    public function getSchemaName()
    {
        return 'test';

    }

    public function getStorableBackendIdentifiers()
    {
        return array(
            'test'
        );

    }

    public function getStorableData()
    {
        return array(
            'data' => array(
                'hello' => 'hello',
                'hello1' => 'hello',
                'hello2' => 'hello',
                'hello3' => 'hello'
            )
        );

    }

    public function getStorableIdentifier()
    {
        return 'test';

    }

}
