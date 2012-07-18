<?php

namespace Heystack\Subsystem\Core\Generate;

use Symfony\Component\Yaml\Yaml;

class YamlGODSchema implements GODSchemaInterface
{

    private $config;

    public function __construct($file)
    {

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

}
