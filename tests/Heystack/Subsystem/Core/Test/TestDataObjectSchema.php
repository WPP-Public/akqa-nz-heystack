<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Generate\DataObjectGeneratorSchemaInterface;
use Heystack\Subsystem\Core\Identifier\Identifier;

/**
 * Class TestDataObjectSchema
 * @package Heystack\Subsystem\Core\Test
 */
class TestDataObjectSchema implements DataObjectGeneratorSchemaInterface
{
    /**
     * @var
     */
    protected $id;
    /**
     * @param $id
     */
    function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getChildStorage()
    {
        return array();
    }

    /**
     * @return mixed
     */
    public function getDataProviderIdentifier()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getFlatStorage()
    {
        return array(
            'Test' => 'Text'
        );
    }
    /**
     * @return \Heystack\Subsystem\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->id);
    }

    /**
     * @return array
     */
    public function getParentStorage()
    {
        return array();
    }

    /**
     * @return array
     */
    public function getRelatedStorage()
    {
        return array();
    }
    /**
     * @param DataObjectGeneratorSchemaInterface $schema
     * @return bool
     */
    public function mergeSchema(DataObjectGeneratorSchemaInterface $schema)
    {
        return false;
    }

}
