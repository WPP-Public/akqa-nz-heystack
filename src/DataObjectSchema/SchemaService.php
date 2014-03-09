<?php

namespace Heystack\Core\DataObjectSchema;

/**
 * Class SchemaService
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