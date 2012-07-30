<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Subsystem\Core\Storage\Backends\SilverStripeOrm;

use Heystack\Subsystem\Core\Storage\StorableInterface;
use Heystack\Subsystem\Core\Storage\BackendInterface;

use Heystack\Subsystem\Core\Storage\Event;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 *
 *
 *
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class Backend implements BackendInterface
{

    const IDENTIFIER = 'silverstripe_orm';

    private $eventService = null;

    public function __construct(EventDispatcher $eventService)
    {

        $this->eventService = $eventService;

    }

    public function getIdentifier() 
    {

        return self::IDENTIFIER;

    }
    
    public function write(StorableInterface $object)
    {

        $data = $object->getStorableData();
        
        $saveable = 'Stored' . $data['id'];

        $storedObject = new $saveable();

        foreach ($data['flat'] as $key => $value) {
            
            if ($key == 'References') {
                
                if (is_array($value)) {
                    
                    foreach ($value as $storableKey => $storableObj) {
                        
                        if ($storableObj instanceof StorableInterface) {
                
                            foreach ($storableObj->getStorableData() as $referenceKey => $referenceValue) {

                                $storedObject->{$key . $referenceKey} = $referenceValue;

                            }
                        }

                    }
                    
                }
                
            } else {

                $storedObject->$key = $value;
                
            }
            
            if ($data['flat'][$key] instanceof StorableInterface) {
                
                foreach ($data['flat'][$key]->getStorableData() as $referenceKey => $referenceValue) {
                
                    $storedObject->{$key . $referenceKey} = $referenceValue;
                    
                }
                
            } else {

                $storedObject->$key = $value;
                
            }

        }

        $storedObject->write();

        if ($data['related']) {
            
            foreach ($data['related'] as $relatedData) {

                $saveable = "Stored{$data['id']}RelatedData";
                $storedManyObject = new $saveable();
                
                foreach ($relatedData['flat'] as $key => $value) {
                    
                    $objectIDName = "Stored{$data['id']}ID";
                    $storedManyObject->$objectIDName = $storedObject->ID;
                    
                    $storableName = $relatedData['id'] . $key;
                    $storedManyObject->$storableName = $value; 

                }
                
                $storedManyObject->write();

            }
            
        }

        $this->eventService->dispatch(
            self::IDENTIFIER . '.' . $object->getStorableIdentifier() . '.stored',
            new Event($storedObject->ID)
        );

    }

}
