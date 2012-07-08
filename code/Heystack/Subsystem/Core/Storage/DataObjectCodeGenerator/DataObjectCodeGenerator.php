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
class DataObjectCodeGenerator {
    
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
    public function addDataObject(DataObject $dataObject)
    {
        
        $this->dataObjects[] = $dataObject;
        
    }
    
    /**
     * Remove a DataObject from the process list.
     * 
     * @param DataObject $dataObject
     */
    public function removeDataObject(DataObject $dataObject)
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
        
		foreach ($this->dataObjects as $dataObject) {

            $dir_base = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache');
            
            $db = $dataObject->getStorableData();

            $singleRelations = $dataObject->getStorableSingleRelations();

            foreach ($singleRelations as $name => $className) {
                
                $storable = singleton($className)->getStorableData();
                
                foreach ($storable as $key => $value) {
                    
                    $db[$name . "_" . $key] = $value;
                    
                }
                
            }
            
			file_put_contents($dir_base . DIRECTORY_SEPARATOR . "Stored" . get_class($dataObject) . '.php', singleton('ViewableData')->renderWith('DataObject_php', array(
				'DataObjectName' => "Stored" . get_class($dataObject),
                'db' => var_export($db, true),
				'D' => '$',
				'P' => '<?php',
			)));
            
            
            
            $manyRelations = $dataObject->getStorableManyRelations();
            
            $db = array();
            
            foreach ($manyRelations as $name => $className) {
                
                $db["Stored" . get_class($dataObject) . "ID"] = 'Int';
               
                
                $storable = singleton($className)->getStorableData();
                
                foreach ($storable as $key => $value) {
                    
                    $db["Stored" . $name . "_" . $key] = $value;
                    
                }
                
                
            }
            
            file_put_contents($dir_base . DIRECTORY_SEPARATOR . "Stored" . get_class($dataObject) . '_' . $className . '.php', singleton('ViewableData')->renderWith('DataObject_php', array(
                    'DataObjectName' => "Stored" . get_class($dataObject) . '_' . $className,
                    'db' => var_export($db, true),
                    'D' => '$',
                    'P' => '<?php',
            )));

			
		}

	}
}