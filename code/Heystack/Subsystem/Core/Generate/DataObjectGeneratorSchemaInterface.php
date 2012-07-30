<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Generate namespace
 */
namespace Heystack\Subsystem\Core\Generate;

/**
 * Defines how a schema should work
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
interface DataObjectGeneratorSchemaInterface
{

    public function getIdentifier();
    public function getFlatStorage();
    public function getRelatedStorage();
    public function getParentStorage();
    public function getChildStorage();
    public function mergeSchema(DataObjectGeneratorSchemaInterface $schema);

}
