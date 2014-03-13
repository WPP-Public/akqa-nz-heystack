<?php

namespace Heystack\Core\DataObjectSchema;

/**
 * Defines how a schema should work
 * 
 * Implementing this interface allows the implementation to be added to the schema service
 * and as such can be used for DataObject generation and for storage
 * 
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @author  Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack\Core\DataObjectSchema
 */
interface SchemaInterface
{
    /**
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier();

    /**
     * @return array
     */
    public function getFlatStorage();

    /**
     * @return bool
     */
    public function getParentStorage();

    /**
     * @return mixed
     */
    public function getChildStorage();

    /**
     * @return bool
     */
    public function getReference();

    /**
     * @param bool $value
     * @return void
     */
    public function setReference($value);

    /**
     * @return mixed
     */
    public function getReplace();

    /**
     * @param bool $value
     * @return void
     */
    public function setReplace($value);

    /**
     * @param  \Heystack\Core\DataObjectSchema\SchemaInterface $schema
     * @return void
     */
    public function mergeSchema(SchemaInterface $schema);
}
