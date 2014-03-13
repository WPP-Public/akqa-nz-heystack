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
            return serialize([$this->record, $this->getExtraData()]);
        }

        return serialize($this->record);
    }

    /**
     * @param $data
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
    }
}
