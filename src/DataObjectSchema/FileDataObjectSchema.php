<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Generate namespace
 */
namespace Heystack\Core\DataObjectSchema;

use Doctrine\Common\Cache\CacheProvider;
use Heystack\Core\Exception\ConfigurationException;
use Heystack\Core\Identifier\Identifier;
use Heystack\Core\Traits\HasStateServiceTrait;

/**
 * An abstract class for schema from files
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @author  Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
abstract class FileDataObjectSchema implements SchemaInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var bool
     */
    private $reference = false;

    /**
     * @var bool
     */
    private $replace = false;

    /**
     * @param string $file
     * @param \Doctrine\Common\Cache\CacheProvider $cache
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
    public function __construct($file, CacheProvider $cache)
    {
        $key = md5($file);

        if (!$config = $cache->fetch($key)) {
            $config = $this->parseFile($file);
            $cache->save($key, $config);
        }

        if (!is_array($config)) {
            throw new ConfigurationException(
                sprintf(
                    "Your config is empty for file '%s'",
                    $file
                )
            );
        }

        if (!array_key_exists('id', $config)) {
            throw new ConfigurationException(
                sprintf(
                    "Identifier missing for file '%s'",
                    $file
                )
            );
        }

        if (!array_key_exists('flat', $config)) {
            throw new ConfigurationException(
                sprintf(
                    "Flat config missing for file '%s'",
                    $file
                )
            );
        }

        $this->config = $config;
    }

    /**
     * @param string $file
     * @return array
     */
    abstract protected function parseFile($file);

    /**
     * @return bool|\Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return isset($this->config['id']) ? new Identifier($this->config['id']) : false;
    }

    /**
     * @return array
     */
    public function getFlatStorage()
    {
        return isset($this->config['flat']) ? $this->config['flat'] : [];
    }

    /**
     * @return array
     */
    public function getParentStorage()
    {
        return isset($this->config['parent']) ? $this->config['parent'] : [];
    }

    /**
     * @return array
     */
    public function getChildStorage()
    {
        return isset($this->config['children']) ? $this->config['children'] : [];
    }

    /**
     * @param boolean $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return boolean
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param boolean $replace
     */
    public function setReplace($replace)
    {
        $this->replace = $replace;
    }

    /**
     * @return boolean
     */
    public function getReplace()
    {
        return $this->replace;
    }

    /**
     * @param  \Heystack\Core\DataObjectSchema\SchemaInterface $schema
     * @return void
     */
    public function mergeSchema(SchemaInterface $schema)
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
