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
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
interface BackendInterface
{
    /**
     * @param  StorableInterface $object
     * @return mixed
     */
    public function write(StorableInterface $object);
    /**
     * @return \Heystack\Subsystem\Core\Identifier\Identifier
     */
    public function getIdentifier();
}
