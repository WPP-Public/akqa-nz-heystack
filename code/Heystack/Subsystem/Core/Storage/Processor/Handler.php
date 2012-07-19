<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Subsystem\Core\Storage\Processor;

use Heystack\Subsystem\Core\Storage\Processor\ProcessorInterface;

use Heystack\Subsystem\Core\Processor\HandlerTrait;

/**
 * Hold and handles storage processors.
 *
 * This class provides storage and processing  of input processors,
 * processors are stored and accessed by an identifier
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */
class Handler
{
    /**
     * Use the handler trait
     */
    use HandlerTrait;

    /**
     * Adds an input processor to the array of processors, storing it by its identifier
     * @param ProcessorInterface $processor The input processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {

        $this->processors[$processor->getIdentifier()] = $processor;

    }

    /**
     * Process a storage processor by identifier if it exists
     *
     * @param  object  $object     The object to store
     * @param  string  $identifier The type of object to store as
     * @return boolean
     */
    public function process($object, $identifier = false, $parentID = false)
    {   

        if (!$identifier) {
            $identifier = $object->getStorageIdentifier();
        }
        
        if ($this->hasProcessor($identifier)) {
            
            return $this->processors[$identifier]->write($object, $parentID);

        } else {

            return false;

        }

    }

}
