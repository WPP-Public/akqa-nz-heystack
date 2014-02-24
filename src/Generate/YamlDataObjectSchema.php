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
use Symfony\Component\Yaml\Yaml;

/**
 * Uses yaml files to provide a schema for dataobject class creation
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @author  Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
class YamlDataObjectSchema extends FileDataObjectSchema
{
    /**
     * @param $file
     * @return array
     * @throws \Heystack\Subsystem\Core\Exception\ConfigurationException
     */
    protected function parseFile($file)
    {
        $file = BASE_PATH . '/' . $file;

        if (!file_exists($file)) {

            throw new ConfigurationException('File doesn\'t exist ' . $file);

        }

        return Yaml::parse($file);

    }
}
