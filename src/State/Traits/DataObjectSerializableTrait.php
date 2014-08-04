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
     * @return string
     */
    public function serialize()
    {
        if ($this instanceof ExtraDataInterface) {
            return serialize([$this->toMap(), $this->getExtraData()]);
        }

        return serialize($this->toMap());
    }

    /**
     * @param string $data
     * @return void
     */
    public function unserialize($data)
    {
        $this->class = get_class($this);

        if ($this instanceof ExtraDataInterface) {
            $unserialized = unserialize($data);
            $this->record = $unserialized[0];
            $this->model = \DataModel::inst();
            $this->setExtraData($unserialized[1]);
        } else {
            $this->record = unserialize($data);
            $this->model = \DataModel::inst();
        }

        // Ensure that the spec is loaded for the class
        $injector = \Injector::inst();
        $config = $injector->getConfigLocator()->locateConfigFor($this->class);
        if ($config) {
            $injector->load([$this->class => $config]);
        }
        $injector->inject($this, $this->class);

        // Ensure that all the extensions are loaded for the class
        foreach(\ClassInfo::ancestry($this->class) as $class) {
            if(in_array($class, ['Object', 'ViewableData', 'RequestHandler'])) continue;
            
            $extensions = \Config::inst()->get($class, 'extensions',
                \Config::UNINHERITED | \Config::EXCLUDE_EXTRA_SOURCES);

            if($extensions) foreach($extensions as $extension) {
                $instance = \Object::create_from_string($extension);
                $instance->setOwner(null, $class);
                $this->extension_instances[$instance->class] = $instance;
            }
        }
    }

    /**
     * An abstract method to ensure that toMap is implemented
     * @return array
     */
    abstract public function toMap();
}
