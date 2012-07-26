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

/**
 * Uses a SilverStripe DataObject to return schema for creation
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
class DataObjectSchema implements DataObjectGeneratorSchemaInterface
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

        return false; //TODO: Related stuff

    }

    public function getParentStorage()
    {

        return false; //TODO: Related stuff

    }

}
