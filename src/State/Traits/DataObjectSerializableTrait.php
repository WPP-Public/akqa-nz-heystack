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
     * @var \ReflectionMethod[]
     */
    private static $objectConstructorReflectionMethods = [];

    /**
     * @var array
     */
    private static $classConfigs = [];

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
        $injector = \Injector::inst();

        $extraData = null;

        if (empty(self::$objectConstructorReflectionMethods[__CLASS__])) {
            self::$objectConstructorReflectionMethods[__CLASS__] = new \ReflectionMethod(__CLASS__, '__construct');
        }

        if (empty(self::$classConfigs[__CLASS__])) {
            self::$classConfigs[__CLASS__] = $injector->getConfigLocator()->locateConfigFor(__CLASS__);
        }

        if ($this instanceof ExtraDataInterface) {
            $unserialized = \Heystack\Core\unserialize($data);

            self::$objectConstructorReflectionMethods[__CLASS__]->invoke(
                $this,
                $unserialized[0]
            );

            $extraData = $unserialized[1];
        } else {
            self::$objectConstructorReflectionMethods[__CLASS__]->invoke(
                $this,
                \Heystack\Core\unserialize($data)
            );
        }

        // Ensure that the spec is loaded for the class
        if (self::$classConfigs[__CLASS__]) {
            $injector->load([__CLASS__ => self::$classConfigs[__CLASS__]]);
        }
        
        $injector->inject($this, __CLASS__);
        
        if ($extraData) {
            $this->setExtraData($extraData);
        }
    }

    /**
     * An abstract method to ensure that toMap is implemented
     * @return array
     */
    abstract public function toMap();
}
