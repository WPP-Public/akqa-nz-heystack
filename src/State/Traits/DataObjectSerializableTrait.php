<?php

namespace Heystack\Core\State\Traits;

use Heystack\Core\State\ExtraDataInterface;

/**
 * Provides a implementation of \Serializable specific to DataObject
 * @package Heystack\Core\State\Traits
 */
trait DataObjectSerializableTrait
{
    /**
     * Stores the Object constructor
     * @var \ReflectionMethod
     */
    private static $objectConstructorReflectionMethod;

    /**
     * @return string
     */
    public function serialize()
    {
        if ($this instanceof ExtraDataInterface) {
            return \Heystack\Core\serialize([$this->toMap(), $this->getExtraData()]);
        }

        return \Heystack\Core\serialize($this->toMap());
    }

    /**
     * This routine simulates what would happen when a object is constructed
     * @param string $data
     * @return void
     */
    public function unserialize($data)
    {
        if (!self::$objectConstructorReflectionMethod) {
            self::$objectConstructorReflectionMethod = new \ReflectionMethod('Object', '__construct');
        }

        // Call the __construct method
        self::$objectConstructorReflectionMethod->invoke($this);

        if ($this instanceof ExtraDataInterface) {
            $unserialized = \Heystack\Core\unserialize($data);
            $this->record = $unserialized[0];
            $this->model = \DataModel::inst();
            $this->setExtraData($unserialized[1]);
        } else {
            $this->record = \Heystack\Core\unserialize($data);
            $this->model = \DataModel::inst();
        }

        // Ensure that the spec is loaded for the class
        $injector = \Injector::inst();
        $config = $injector->getConfigLocator()->locateConfigFor($this->class);

        if ($config) {
            $injector->load([$this->class => $config]);
        }
        
        $injector->inject($this, $this->class);
    }

    /**
     * An abstract method to ensure that toMap is implemented
     * @return array
     */
    abstract public function toMap();
}
