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

/**
 * Interface for storage processors
 *
 * Storage processors need to implement this interface in order to be added to a processors handler.
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
interface ProcessorInterface
{

    /**
     * Returns the identifier of the processor
     * @return string Identifier of processor
     */
    public function getIdentifier();
    
    /**
     * Execute the main functionality of the storage process
     * 
     * @param object $object The object to store
     */
    public function process($object);

}
