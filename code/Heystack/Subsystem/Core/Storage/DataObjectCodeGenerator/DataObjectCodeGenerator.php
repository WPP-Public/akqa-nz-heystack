<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Heystack\Subsystem\Core\Storage\DataObjectCodeGenerator namespace
 */
namespace Heystack\Subsystem\Core\Storage\DataObjectCodeGenerator;

use \Heystack\Subsystem\Core\State\ExtraDataInterface;

/**
 * DataObjectCodeGenerator generates DataObjects to use in the storage of
 * information in state.
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
class DataObjectCodeGenerator
{
    /**
     * DataObject list to process
     *
     * @var array
     */
    private $dataObjects = array();

    /**
     * Add a dataobject to the process list.
     *
     * @param DataObject $dataObject
     */
    public function addDataObject($dataObject)
    {

        $this->dataObjects[] = $dataObject;

    }

    /**
     * Remove a DataObject from the process list.
     *
     * @param DataObject $dataObject
     */
    public function removeDataObject($dataObject)
    {

        unset($this->dataObjects[$dataObject]);

    }

    /**
     * Set the process list.
     *
     * @param array $dataObjects
     */
    public function setDataObjects(array $dataObjects)
    {

        $this->dataObjects = $dataObjects;

    }

    /**
     * Process the dataobjects stored in the process list and output new cached
     * DataObject's for storage.
     *
     */
    public function process()
    {

        // get all the previously created objects and delete them
        $cachedFiles = glob(BASE_PATH . '/heystack/cache/Stored*', GLOB_NOSORT);

        foreach ($cachedFiles as $cachedFile) {

            unlink($cachedFile);

        }

        foreach ($this->dataObjects as $dataObject) {

            $dir_base = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache');

            // get the storable db for the object
            $db = $dataObject->getStorableData();

            // store the extra data on the object
            if ($dataObject instanceof ExtraDataInterface) {

                $extraData = $dataObject->getExtraData();

                foreach ($extraData as $name => $value) {

                        $db[$name] = 'Text';

                }

            }

            // store the has_one relations on the dataobject
            $singleRelations = $dataObject->getStorableSingleRelations();

            if ($singleRelations) {

                foreach ($singleRelations as $name => $className) {

                    $storable = singleton($className)->getStorableData();

                    foreach ($storable as $key => $value) {

                        $db[$name . "-" . $key] = $value;

                    }

                }

            }

            file_put_contents($dir_base . DIRECTORY_SEPARATOR . "Stored" . get_class($dataObject) . '.php', singleton('ViewableData')->renderWith('DataObject_php', array(
                'DataObjectName' => "Stored" . get_class($dataObject),
                'db' => var_export($db, true),
                'D' => '$',
                'P' => '<?php',
                'has_many' => var_export(array('Stored' .get_class($dataObject) . 'RelatedData' => 'Stored' .get_class($dataObject) . 'RelatedData'), true),
            )));



            $manyRelations = $dataObject->getStorableManyRelations();

            $db = array();

            if ($manyRelations) {

                foreach ($manyRelations as $name => $className) {

                    $storable = singleton($className)->getStorableData();

                    foreach ($storable as $key => $value) {

                        $db[$name . "-" . $key] = $value;

                    }

                }
            }

            file_put_contents($dir_base . DIRECTORY_SEPARATOR . "Stored" . get_class($dataObject) . 'RelatedData.php', singleton('ViewableData')->renderWith('DataObject_php', array(
                        'DataObjectName' => "Stored" . get_class($dataObject) . 'RelatedData',
                        'db' => var_export($db, true),
                        'D' => '$',
                        'P' => '<?php',
                        'has_one' => var_export(array('Stored' .get_class($dataObject) => 'Stored' .get_class($dataObject)), true),
                        'has_many' => false
            )));

        }

    }
}
