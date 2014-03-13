<?php

namespace Heystack\Core\Storage;

/**
 * Interface for storable objects
 *
 * Requires implementing classes to write required methods for storing themselves
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @author  Glenn Bautista <glenn@heyday.co.nz>
 * @author  Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
interface StorableInterface
{
    /**
     * @return string
     */
    public function getStorableIdentifier();

    /**
     * @return array
     */
    public function getStorableData();

    /**
     * @return array
     */
    public function getStorableBackendIdentifiers();

    /**
     * @return string
     */
    public function getSchemaName();
}
