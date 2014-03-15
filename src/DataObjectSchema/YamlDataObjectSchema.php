<?php

namespace Heystack\Core\DataObjectSchema;

use Heystack\Core\Exception\ConfigurationException;
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
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
    protected function parseFile($file)
    {
        if (!file_exists($file)) {
            $file = BASE_PATH . '/' . $file;
        }

        if (!file_exists($file)) {
            throw new ConfigurationException(
                sprintf(
                    "File '%s' doesn't exist",
                    $file
                )
            );
        }

        return Yaml::parse($file);
    }
}
