<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Storage\StorableInterface;

class TestStorable implements StorableInterface
{

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
