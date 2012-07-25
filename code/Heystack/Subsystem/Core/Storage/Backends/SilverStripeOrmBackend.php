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
class SilverStripeOrmBackend implements BackendInterface
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

            $storedObject->$key = $data['flat'][$key];

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
            $this->getIdentifier() . '.' . $object->getIdentifier() . '.stored',
            new Event($storedObject->ID)
        );

    }

}
