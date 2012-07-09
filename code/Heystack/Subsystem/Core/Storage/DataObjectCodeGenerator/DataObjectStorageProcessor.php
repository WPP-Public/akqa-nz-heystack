<?php

namespace Heystack\Subsystem\Core\Storage\DataObjectCodeGenerator;

use \Heystack\Subsystem\Core\Storage\ProcessorInterface;

class DataObjectStorageProcessor implements ProcessorInterface
{
    

    public function process($dataObject) {
        
        $saveable = "Stored" . $dataObject->ClassName;
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
                
                $data_name = $name . "_" . $key;
                $storedObject->$data_name = $data->$key;
                
            }

        }
        
        $storedObject->write();
        
        
        echo "<pre>";

        
        //deal with the has_many relations
        $manyRelations = $dataObject->getStorableManyRelations();
        
        foreach ($manyRelations as $name => $className) {
            
            $saveable = "Stored" . $dataObject->ClassName . "_" . $className;
            $storedManyObject = new $saveable();
           
            // save the ID of the object we are related to
            $objectIDName = "Stored" . get_class($dataObject) . "ID";
            $storedManyObject->$objectIDName = $storedObject->ID;
   
            
            echo $className . " = ";
                       
            echo $dataObject->getReverseAssociation($className) . "\n";

            //echo  $name  . ", " . $className . "  \n\n\n\n\n";
            
            $storedDataObjects = \DataObject::get($className, $name = $dataObject->ID);
            
            if ($storedDataObjects && $storedDataObjects->exists()) {
                foreach ($storedDataObjects as $object) {

    //                foreach ($fields as $key => $value) {
    //
    //                    $data_name = "Stored" . $name . "_" . $key;
    //          
    //                    $manyObject->$data_name = $data->$key;
    //
    //                }
                }
            }

        }
        
        $storedManyObject->write();
        
    }

    public function getIdentifier()
    {
        return "dataobjectstorage";
    }
    
}

