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
 * Uses json files to provide a schema for dataobject class creation
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class JsonDataObjectSchema extends FileDataObjectSchema
{

    protected function parseFile($file)
    {

        return json_decode(file_get_contents($file), true);

    }

}
