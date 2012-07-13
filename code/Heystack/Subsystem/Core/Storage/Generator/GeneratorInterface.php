<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Subsystem\Core\Storage\Generator;

/**
 * Interface for storage generators
 *
 * Storage generators need to implement this interface in order to be added to a generators handler.
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
interface GeneratorInterface
{
    /**
     * Returns the identifier of the generator
     * @return string Identifier of generator
     */
    public function getIdentifier();
    
    /**
     * Execute the main functionality of the storage generators
     */
    public function process();

}
