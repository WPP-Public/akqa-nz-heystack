<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */
/**
 * Heystack\Subsystem\Core\Storage\DataObjectCodeGenerator namespace
 */
namespace Heystack\Subsystem\Core\Storage\DataObjectStorage;

use Heystack\Subsystem\Core\Storage\Processor\ProcessorInterface;

/**
 * DataObjectStorageProcessor processes dataobjects and stores their defined
 * storable values.
 *
 * Because we need to be able to store all data persistently in a database we
 * will use the generated classes to store relevant information for each
 * dataobject.
 *
 * @copyright  Heyday
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 *
 */
class DataObjectStorageProcessor implements ProcessorInterface
{

    /**
     * Process dataobjects to store and store their relevant defined
     * storage values.
     *
     */
    public function process($dataObject)
    {

        $saveable = "Stored" . array_pop(explode('\\', $dataObject->ClassName));
        $storedObject = new $saveable();

        // save all $db fields from a silverstripe object
        $db = $dataObject->getStorableData();

        foreach ($db as $key => $value) {
            $storedObject->$key = $dataObject->$key;
        }

        // save all $has_one fields from a related silverstripe object
        $singleRelations = $dataObject->getStorableSingleRelations();

        foreach ($singleRelations as $name => $className) {

            $fields = singleton($className)->getStorableData();

            $IDkey = $name ."ID";
            $data = \DataObject::get_by_id($className, $dataObject->$IDkey);

            foreach ($fields as $key => $value) {

                $data_name = $name . "-" . $key;
                $storedObject->$data_name = $data->$key;

            }

        }

        $dataObjectClass = new \ReflectionClass($dataObject->ClassName);
        if ($dataObjectClass->implementsInterface('Heystack\Subsystem\Core\State\ExtraDataInterface')) {

            $extraData = $dataObject->getExtraData();

            foreach ($extraData as $name => $value) {

                $storedObject->$name = $value;

            }

        }

        $storedObject->write();

        //deal with the has_many relations
        $manyRelations = $dataObject->getStorableManyRelations();

        foreach ($manyRelations as $manyKey => $className) {

            $storedDataObjects = new \DataObjectSet();
            if (!$dataObject->many_many($manyKey)) {

                $storedDataObjects->merge(\DataObject::get($className, "{$dataObject->ClassName}ID = $dataObject->ID"));

            } else {

                $storedDataObjects->merge($dataObject->$manyKey());

            }

            if ($storedDataObjects && $storedDataObjects->exists()) {

                foreach ($storedDataObjects as $object) {

                    // create the object to save
                    $saveable = "Stored" . $dataObject->ClassName . "RelatedData";
                    $storedManyObject = new $saveable();

                    // save the ID of the object we are related to
                    $objectIDName = "Cached" . get_class($dataObject) . "ID";
                    $storedManyObject->$objectIDName = $storedObject->ID;

                    // get the storable fields of this object
                    $storableFields = $object->getStorableData();

                    foreach ($storableFields as $key => $value) {

                        $storableName = $manyKey . "-" . $key;

                        $storedManyObject->$storableName = $object->$key;

                    }

                    $storedManyObject->write();

                }

            }

        }

    }

    /**
     * Return identifier for DataObjectStorageProcessor
     *
     * @return string Identifier
     */
    public function getIdentifier()
    {
        return "dataobject";
    }

}
