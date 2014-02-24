<?php

namespace Heystack\Core\Test;

use Heystack\Core\Identifier\Identifier;
use Heystack\Core\Storage\StorableInterface;
use Heystack\Core\Storage\BackendInterface;

/**
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class TestStorageBackend implements BackendInterface
{

    /**
     * @return \Heystack\Core\Identifier\Identifier
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
