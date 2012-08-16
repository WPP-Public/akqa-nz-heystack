<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Storage\StorableInterface;
use Heystack\Subsystem\Core\Storage\BackendInterface;

/**
 *
 *
 *
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class TestStorageBackend implements BackendInterface
{

    public function getIdentifier()
    {

        return 'test';

    }

    public function write(StorableInterface $object)
    {
        
        return $object->getStorableData();

    }

}
