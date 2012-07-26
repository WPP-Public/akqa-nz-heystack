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

use Symfony\Component\Yaml\Yaml;

/**
 * Uses yaml files to provide a schema for dataobject class creation
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
class YamlDataObjectGeneratorSchema implements DataObjectGeneratorSchemaInterface
{

    private $config;

    public function __construct($file)
    {

        if (!file_exists(BASE_PATH . '/' . $file)) {

            throw new \Exception('File doesn\'t exist');

        }

        $config = Yaml::parse(BASE_PATH . '/' . $file);

        if (!is_array($config)) {

            throw new \Exception('Config sucks ballzors');

        }

        if (!array_key_exists('id', $config)) {

            throw new \Exception('You need to name your DataObject');

        }

        if (!array_key_exists('flat', $config)) {

            throw new \Exception('Flat config didn\'t exist');

        }

        if (!array_key_exists('related', $config)) {

            throw new \Exception('Related config didn\'t exist');

        }

        $this->config = $config;

    }

    public function getIdentifier()
    {

        return isset($this->config['id']) ? $this->config['id'] : false;

    }

    public function getFlatStorage()
    {

        return isset($this->config['flat']) ? $this->config['flat'] : false;

    }

    public function getRelatedStorage()
    {

        return isset($this->config['related']) ? $this->config['related'] : false;

    }

    public function getParentStorage()
    {

        return isset($this->config['parent']) ? $this->config['parent'] : false;

    }

    public function getChildStorage()
    {

        return isset($this->config['children']) ? $this->config['children'] : array();

    }

    public function getReferenceOnly()
    {

        return isset($this->config['reference']) ? true : false;

    }

}
