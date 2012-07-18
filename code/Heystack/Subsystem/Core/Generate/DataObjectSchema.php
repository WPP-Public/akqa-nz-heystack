<?php

namespace Heystack\Subsystem\Core\Generate;

class DataObjectSchema implements GODSchemaInterface
{

    private $dataObject;

    public function __construct($className)
    {

        if (class_exists($className)) {

            $this->dataObject = new $className;

        } else {

            throw new \Exception('DataObject doesn\'t exist');

        }

    }

    public function getIdentifier()
    {

        return $this->dataObject->ClassName;

    }

    public function getFlatStorage()
    {

        return $this->dataObject->db();

    }

    public function getRelatedStorage()
    {

        return false;

        return isset($this->config['related']) ? $this->config['related'] : false;

    }

}
