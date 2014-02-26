<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Generate namespace
 */
namespace Heystack\Core\Generate;

    /**
     * Defines how a schema should work
     *
     * @author  Cam Spiers <cameron@heyday.co.nz>
     * @author  Stevie Mayhew <stevie@heyday.co.nz>
     * @package Heystack
     */
/**
 * Class DataObjectGeneratorSchemaInterface
 * @package Heystack\Core\Generate
 */
interface DataObjectGeneratorSchemaInterface
{
    /**
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier();

    /**
     * @return mixed
     */
    public function getDataProviderIdentifier();

    /**
     * @return mixed
     */
    public function getFlatStorage();

    /**
     * @return mixed
     */
    public function getRelatedStorage();

    /**
     * @return mixed
     */
    public function getParentStorage();

    /**
     * @return mixed
     */
    public function getChildStorage();

    /**
     * @param  DataObjectGeneratorSchemaInterface $schema
     * @return mixed
     */
    public function mergeSchema(DataObjectGeneratorSchemaInterface $schema);
}
