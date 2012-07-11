<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Core namespace
 */
namespace Heystack\Subsystem\Core\Output;

/**
 * Interface for input processors
 *
 * Input processors need to implement this interface in order to be added to a processors handler.
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
interface ProcessorInterface
{
    /**
     * Returns the identifier of the processor
     * 
     * @return string Identifier of processor
     */
    public function getIdentifier();
    
    /**
     * Executes the main functionality of the output processor
     * 
     * @param \Controller $controller The relevant SilverStripe controller
     * @param mixed $result The result from the input processor
     */
    public function process(\Controller $controller, $result = null);

}
