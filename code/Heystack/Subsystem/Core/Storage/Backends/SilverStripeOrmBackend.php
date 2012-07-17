<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Subsystem\Core\Storage\Backends;

use Heystack\Subsystem\Core\Storage;

/**
 * 
 *
 * 
 * 
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class SilverStripeOrmBackend implements BackendInterface
{

    public function write(StorageInterface $object)
    {

        $data = $object->getStorableData();

        

    }

}