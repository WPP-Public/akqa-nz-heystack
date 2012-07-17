<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Subsystem\Core\Storage;

/**
 * 
 *
 * 
 * 
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
interface BackendInterface
{

    public function write(StorableInterface $object);
    public function read();

}