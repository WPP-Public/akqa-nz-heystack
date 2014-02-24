<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * DataObjectHandler namespace
 */
namespace Heystack\Subsystem\Core\DataObjectHandler;

/**
 * Generates SilverStripe DataObject classes based of schemas
 *
 * Generates SilverStripe DataObject classes and extensions based on added schemas
 *
 * @author  Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */

interface DataObjectHandlerInterface
{

    /**
     * Return the first item matching the given query.
     *
     * @param string  $callerClass The class of objects to be returned
     * @param string  $filter      A filter to be inserted into the WHERE clause
     * @param boolean $cache       Use caching
     * @param string  $orderby     A sort expression to be inserted into the ORDER BY clause.
     *
     * @return DataObject The first item matching the query
     */
    public function getDataObject($callerClass, $filter = "", $cache = true, $orderby = "");

    /**
     * Return all objects matching the filter
     * sub-classes are automatically selected and included
     *
     * @param string       $callerClass    The class of objects to be returned
     * @param string       $filter         A filter to be inserted into the WHERE clause.
     * @param string|array $sort           A sort expression to be inserted into the ORDER BY clause.  If omitted, self::$default_sort will be used.
     * @param string       $join           A single join clause.  This can be used for filtering, only 1 instance of each DataObject will be returned.
     * @param string|array $limit          A limit expression to be inserted into the LIMIT clause.
     * @param string       $containerClass The container class to return the results in.
     *
     * @return mixed The objects matching the filter, in the class specified by $containerClass
     */
    public function getDataObjects(
        $callerClass,
        $filter = "",
        $sort = "",
        $join = "",
        $limit = "",
        $containerClass = "DataObjectSet"
    );

    /**
     * Return the given element, searching by ID
     *
     * @param string  $callerClass The class of the object to be returned
     * @param int     $id          The id of the element
     * @param boolean $cache       See {@link get_one()}
     *
     * @return DataObject The element
     */
    public function getDataObjectById($callerClass, $id, $cache = true);

    /**
     * Delete the record with the given ID.
     *
     * @param string $className The class name of the record to be deleted
     * @param int    $id        ID of record to be deleted
     */
    public function deleteDataObjectById($className, $id);

}
