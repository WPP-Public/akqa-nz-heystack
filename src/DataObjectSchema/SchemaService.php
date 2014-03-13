<?php

namespace Heystack\Core\DataObjectSchema;

/**
 * This service has schemas added to it and provides available schemas to other services
 * 
 * Schemas can be added to this service as long as the schema implements the SchemaInterface
 * 
 * There are available implementations for JSON and for Yaml.
 * 
 * Other services like the DataObjectGenerator service and the Storage service use this service 
 * in order to get the available schemas.
 * 
 * There is an idea of a reference schema which are available to other schema to reference (use)
 * but aren't used in any direct generation
 * 
 * @package Heystack\Core\DataObjectSchema
 */
class SchemaService
{
    /**
     * @var \Heystack\Core\DataObjectSchema\SchemaInterface[]
     */
    private $schemas = [];

    /**
     * @var \Heystack\Core\DataObjectSchema\SchemaInterface[]
     */
    private $referenceSchemas = [];

    /**
     * @param \Heystack\Core\DataObjectSchema\SchemaInterface $schema
     */
    public function addSchema(SchemaInterface $schema)
    {
        $identifier = strtolower($schema->getIdentifier()->getFull());

        if ($schema->getReference()) {
            $this->referenceSchemas[$identifier] = $schema;
        } else {
            if ($this->hasSchema($identifier) && !$schema->getReplace()) {
                $this->schemas[$identifier]->mergeSchema($schema);
            } else {
                $this->schemas[$identifier] = $schema;
            }
        }
    }

    /**
     * @param $identifier
     * @return bool
     */
    public function hasSchema($identifier)
    {
        return isset($this->schemas[$identifier]) || isset($this->referenceSchemas[$identifier]);
    }

    /**
     * @param string $identifier
     * @throws \InvalidArgumentException
     * @return \Heystack\Core\DataObjectSchema\SchemaInterface
     */
    public function getSchema($identifier)
    {
        if (isset($this->schemas[$identifier])) {
            return $this->schemas[$identifier];
        } elseif (isset($this->referenceSchemas[$identifier])) {
            return $this->referenceSchemas[$identifier];
        } else {
            throw new \InvalidArgumentException(sprintf("Schema '%s' not found", $identifier));
        }
    }

    /**
     * @return \Heystack\Core\DataObjectSchema\SchemaInterface[]
     */
    public function getSchemas()
    {
        return $this->schemas;
    }
} 