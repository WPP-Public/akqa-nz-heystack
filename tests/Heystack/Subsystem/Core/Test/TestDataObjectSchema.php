<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Generate\DataObjectGeneratorSchemaInterface;

class TestDataObjectSchema implements DataObjectGeneratorSchemaInterface
{

    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    public function getChildStorage()
    {

        return array();
    }

    public function getDataProviderIdentifier()
    {

        return $this->id;
    }

    public function getFlatStorage()
    {

        return array(
            'Test' => 'Text'
        );
    }

    public function getIdentifier()
    {

        return $this->id;
    }

    public function getParentStorage()
    {

        return array();
    }

    public function getRelatedStorage()
    {

        return array();
    }

    public function mergeSchema(DataObjectGeneratorSchemaInterface $schema)
    {

        return false;
    }

}
