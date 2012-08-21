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

use Heystack\Subsystem\Core\Exception\ConfigurationException;

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

        if (!file_exists($file)) {

            throw new ConfigurationException('File doesn\'t exist');

        }

        return json_decode(file_get_contents($file), true);

    }

}
