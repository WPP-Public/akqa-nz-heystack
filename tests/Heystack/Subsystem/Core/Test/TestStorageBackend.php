<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Identifier\Identifier;
use Heystack\Subsystem\Core\Storage\StorableInterface;
use Heystack\Subsystem\Core\Storage\BackendInterface;

/**
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class TestStorageBackend implements BackendInterface
{

    /**
     * @return \Heystack\Subsystem\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier('test');
    }

    /**
     * @param  StorableInterface $object
     * @return mixed
     */
    public function write(StorableInterface $object)
    {
        return $object->getStorableData();
    }
}
