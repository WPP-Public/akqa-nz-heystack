<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Core namespace
 */
namespace Heystack\Subsystem\Core;

/**
 * Provides global configuration storage.
 *
 * Config is a globally accessible configuration class
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class Config
{

    /**
     * Data stored on config
     * @var array
     */
    private $data = array();

    /**
     * Sets something on to a config identifier
     * @param string $identifier The identifier to set to
     * @param mixed  $config     The information to set
     */
    public function setConfig($identifier, $config)
    {

        $this->data[$identifier] = $config;

    }
    /**
     * Gets a config if it exists
     * @param  string $identifier The identifier to get
     * @return mixed  The configuration on the identifier if it exists
     */
    public function getConfig($identifier)
    {

        return array_key_exists($identifier, $this->data)
            ? $this->data[$identifier]
            : false;

    }

    /**
     * Remove a set config by an identifier
     * @param  string $identifier The identifier to remove
     * @return null
     */
    public function removeConfig($identifier)
    {

        if (array_key_exists($identifier, $this->data)) {

            unset($this->data[$identifier]);

        }

    }

}
