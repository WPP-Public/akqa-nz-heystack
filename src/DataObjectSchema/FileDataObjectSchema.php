<?php

namespace Heystack\Core\DataObjectSchema;

use Doctrine\Common\Cache\CacheProvider;
use Heystack\Core\Exception\ConfigurationException;
use Heystack\Core\Identifier\Identifier;
use Heystack\Core\Traits\HasStateServiceTrait;

/**
 * An abstract class for schema from files
 * 
 * This abstract class provides a base implementation for file based schema services,
 * such as Yaml and JSON.
 * 
 * When provided a cache, this class provides caching, which will do automatic invalidation
 * when the contents of the file changes.
 * 
 * To extend this class, the extending class needs to provide a "parse" method
 * which should return an array
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
        // if it isn't an absolute path (detected by the file not existing)
        if (!file_exists($file)) {
            $file = BASE_PATH . '/' . $file;
        }
        
        if (!file_exists($file)) {
            throw new ConfigurationException(
                sprintf(
                    "Your file '%s' doesn't exist",
                    $file
                )
            );
        }

        // Use the contents as the key so invalidation happens on change
        $key = md5(file_get_contents($file));

        if (($config = $cache->fetch($key)) === false) {
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
        return isset($this->config['flat']) && is_array($this->config['flat']) ? $this->config['flat'] : [];
    }

    /**
     * @return array
     */
    public function getParentStorage()
    {
        return isset($this->config['parent']) && is_array($this->config['parent']) ? $this->config['parent'] : [];
    }

    /**
     * @return array
     */
    public function getChildStorage()
    {
        return isset($this->config['children']) && is_array($this->config['children']) ? $this->config['children'] : [];
    }

    /**
     * @param bool $reference
     * @return void
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return bool
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param bool $replace
     * @return void
     */
    public function setReplace($replace)
    {
        $this->replace = $replace;
    }

    /**
     * @return bool
     */
    public function getReplace()
    {
        return $this->replace;
    }

    /**
     * @param \Heystack\Core\DataObjectSchema\SchemaInterface $schema
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
