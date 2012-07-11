<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Interface namespace
 */
namespace Heystack\Subsystem\Core\Storage\DataObjectCodeGenerator\Interfaces;

/**
 * DataObjectCodeGeneratorInterface imposes implementation details for
 * DataObjects to be able to be stored.
 *
 * Because we need to be able to store all data persistently in a database we
 * need to be able to implement a consistent way of getting the data which is
 * considered storable.
 *
 * @copyright  Heyday
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 *
 */
interface DataObjectCodeGeneratorInterface
{
    /**
     * Get the relevant storable data
     *
     * @return array
     */
    public function getStorableData();

    /**
     * Get the relevant storable has_one relations
     *
     * @return array
     */
    public function getStorableSingleRelations();

    /**
     * Get the relevant storable has_many and many_many relations
     *
     * @return array
     */
    public function getStorableManyRelations();

}
