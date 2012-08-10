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
 * Interface for storable objects
 *
 * Requires implementing classes to write required methods for storing themselves
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
interface StorableInterface
{

    public function getStorableIdentifier();
    public function getStorableData();
    public function getStorableBackendIdentifiers();
    public function getSchemaName();

}
