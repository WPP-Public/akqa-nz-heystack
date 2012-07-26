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
class SilverStripeOrmBackend implements BackendInterface
{

    public function getIdentifier()
    {

        return 'silverstripe_orm';

    }

    public function write(StorableInterface $object, $parentID = false)
    {

        $data = $object->getStorableData();

        $saveable = 'Stored' . $data['id'];

        $storedObject = new $saveable();

        foreach ($data['flat'] as $key => $value) {
            $storedObject->$key = $data['flat'][$key];
        }

        if ($data['parent']) {

            $storedObject->ParentID = $parentID;

        }

        $storedObject->write();

        if ($data['related']) {

            foreach ($data['related'] as $relatedData) {

                $saveable = "Stored" . $data['id'] . "RelatedData";
                $storedManyObject = new $saveable();

                foreach ($relatedData['flat'] as $key => $value) {

                    $objectIDName = "Stored" . $data['id'] . "ID";
                    $storedManyObject->$objectIDName = $storedObject->ID;

                    $storableName = $relatedData['id'] . $key;
                    $storedManyObject->$storableName = $value;

                }

                $storedManyObject->write();

            }

        }

        return $storedObject->ID;

    }

}
