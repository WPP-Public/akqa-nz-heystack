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

use Heystack\Core\Exception\ConfigurationException;

/**
 * Uses json files to provide a schema for dataobject class creation
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class JsonDataObjectSchema extends FileDataObjectSchema
{
    /**
     * @param $file
     * @return mixed
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
    protected function parseFile($file)
    {

        if (!file_exists($file)) {

            throw new ConfigurationException('File doesn\'t exist');

        }

        return json_decode(file_get_contents($file), true);

    }
}
