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

use Heystack\Subsystem\Core\State\StateableInterface;
use Heystack\Subsystem\Core\State\State;

use Symfony\Component\Yaml\Yaml;

/**
 * Uses yaml files to provide a schema for dataobject class creation
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
class YamlDataObjectGeneratorSchema implements DataObjectGeneratorSchemaInterface, StateableInterface
{

    private $config;

    private $stateService;

    private $stateKey;

    public function __construct($file, State $stateService)
    {
        $this->stateService = $stateService;

        $this->stateKey = md5($file);

        if (!$this->restoreState() || isset($_GET['flush'])) {

            if (!file_exists(BASE_PATH . '/' . $file)) {

                throw new \Exception('File doesn\'t exist');

            }

            $config = Yaml::parse(BASE_PATH . '/' . $file);

            if (!is_array($config)) {

                throw new \Exception('Your config is empty');

            }

            if (!array_key_exists('id', $config)) {

                throw new \Exception('Identifier missing');

            }

            if (!array_key_exists('flat', $config)) {

                throw new \Exception('Flat config missing');

            }

            if (!array_key_exists('related', $config)) {

                throw new \Exception('Related config missing');

            }

             $this->config = $config;

             $this->saveState();

        }

    }

    public function saveState()
    {
        $this->stateService->setByKey($this->stateKey, $this->config);
    }

    public function restoreState()
    {
        return $this->config = $this->stateService->getByKey($this->stateKey);
    }

    public function getIdentifier()
    {

        return isset($this->config['id']) ? $this->config['id'] : false;

    }

    public function getDataProviderIdentifier()
    {

        return isset($this->config['data_provider_id']) ? $this->config['data_provider_id'] : false;

    }

    public function getFlatStorage()
    {

        return isset($this->config['flat']) ? $this->config['flat'] : array();

    }

    public function getRelatedStorage()
    {

        return isset($this->config['related']) ? $this->config['related'] : array();

    }

    public function getParentStorage()
    {

        return isset($this->config['parent']) ? $this->config['parent'] : array();

    }

    public function getChildStorage()
    {

        return isset($this->config['children']) ? $this->config['children'] : array();

    }

    public function mergeSchema(DataObjectGeneratorSchemaInterface $schema)
    {

        $flat = $schema->getFlatStorage();

        if (is_array($flat)) {

            foreach ($flat as $key => $value) {

                $this->config['flat'][$key] = $value;

            }

        }

        $children = $schema->getChildStorage();

        if (is_array($children)) {

            foreach ($children as $key => $value) {

                $this->config['children'][$key] = $value;

            }

        }

    }

}
