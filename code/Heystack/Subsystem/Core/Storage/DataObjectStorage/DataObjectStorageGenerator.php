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

use \Heystack\Subsystem\Core\State\ExtraDataInterface;
use Heystack\Subsystem\Core\Storage\Generator\GeneratorInterface;

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
class DataObjectStorageGenerator implements GeneratorInterface
{
    /**
     * DataObject list to process
     *
     * @var array
     */
    private $dataObjects = array();

    /**
     * Get the identifier for this processor
     * 
     * @return string Identfier
     */
    public function getIdentifier()
    {
        return "dataobjectcodegenerator";
    }
    
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
        
        $dir_mysite = BASE_PATH . DIRECTORY_SEPARATOR . "mysite/code/HeystackStorage";
        $dir_cache = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache');
        
        // check if the generated directoru exists, if not create it
        if (!is_dir($dir_mysite)) {
            mkdir($dir_mysite );
        }
        
        // check if the cached directoru exists, if not create it
        if (!is_dir($dir_cache)) {
            mkdir($dir_cache );
        }
        
        foreach ($this->dataObjects as $dataObject) {

            

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
            
            // names for the created objects
            $cachedObjectName = "Cached" . array_pop(explode('\\', get_class($dataObject)));
            $storedObjectName = "Stored" . array_pop(explode('\\', get_class($dataObject)));
            $cachedRelatedObjectName = "Cached" . array_pop(explode('\\', get_class($dataObject))) . "RelatedData";
            $storedRelatedObjectName = "Stored" . array_pop(explode('\\', get_class($dataObject))) . "RelatedData";

            
               
            // create the cached object
            file_put_contents($dir_cache . DIRECTORY_SEPARATOR . $cachedObjectName . '.php', singleton('ViewableData')->renderWith('CachedDataObject_php', array(
                'DataObjectName' => $cachedObjectName,
                'db' => var_export($db, true),
                'D' => '$',
                'P' => '<?php',
                'has_one' => false,
                'has_many' => var_export(array($storedRelatedObjectName => $storedRelatedObjectName), true),
            )));
            
            // create the storage object
            if (!file_exists($dir_mysite . DIRECTORY_SEPARATOR . $storedObjectName . '.php')) {
                
                
                file_put_contents($dir_mysite . DIRECTORY_SEPARATOR . $storedObjectName . '.php', singleton('ViewableData')->renderWith('StoredDataObject_php', array(
                    'DataObjectName' => $storedObjectName,
                    'D' => '$',
                    'P' => '<?php',
                    'ExtendedDataObject' => $cachedObjectName,
                    'has_one' => false,
                    'has_many' => false,
                    'summary_fields' => var_export(array_keys($db), true),
                    
                )));
                
            }
  
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
            
            if ($dataObject instanceof ExtraDataInterface) {

                $extraData = $dataObject->getExtraData();

                foreach ($extraData as $name => $value) {

                        $db[$name] = 'Text';

                }

            }
            
            if (count($db) > 0) {
            
                // create the cached object
                file_put_contents($dir_cache . DIRECTORY_SEPARATOR . $cachedRelatedObjectName . '.php', singleton('ViewableData')->renderWith('CachedDataObject_php', array(
                            'DataObjectName' => $cachedRelatedObjectName,
                            'db' => var_export($db, true),
                            'D' => '$',
                            'P' => '<?php',
                            'has_one' => var_export(array($cachedObjectName => $cachedObjectName), true),
                            'has_many' => false
                )));       

                // create the storage object
                if (!file_exists($dir_mysite . DIRECTORY_SEPARATOR . $storedRelatedObjectName . '.php')) {


                    file_put_contents($dir_mysite . DIRECTORY_SEPARATOR . $storedRelatedObjectName . '.php', singleton('ViewableData')->renderWith('StoredDataObject_php', array(
                        'DataObjectName' => $storedRelatedObjectName,
                        'D' => '$',
                        'P' => '<?php',
                        'ExtendedDataObject' => $cachedRelatedObjectName,
                        'has_one' => false,
                        'has_many' => false,
                        'summary_fields' => var_export(array_keys($db), true),

                    )));

                }
            }

        }

    }
}
