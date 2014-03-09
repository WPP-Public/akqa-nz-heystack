<?php

namespace Heystack\Core\ViewableData;

/**
 * Class CastObjectTrait
 * 
 * This class is used for allowing functions on classes extending ViewableData to return
 * objects and have them casted as long as the object returned doesn't extend ViewableData
 * 
 * Warning this trait disables the object cache used in templates
 * 
 * @package Heystack\Core\ViewableData
 */
trait CastObjectTrait
{
    /**
     * @param $fieldName
     * @param null $arguments
     * @param bool $forceReturnedObject
     * @param bool $cache
     * @param null $cacheName
     * @return mixed
     */
    public function obj($fieldName, $arguments = null, $forceReturnedObject = true, $cache = false, $cacheName = null)
    {
        // HACK: Don't call the deprecated FormField::Name() method
        $methodIsAllowed = true;

        if ($this instanceof \FormField && $fieldName == 'Name') {
            $methodIsAllowed = false;
        }

        if ($methodIsAllowed && $this->hasMethod($fieldName)) {
            $value = $arguments ? call_user_func_array(array($this, $fieldName), $arguments) : $this->$fieldName();
        } else {
            $value = $this->$fieldName;
        }

        if (!$value instanceof \ViewableData && ($this->castingClass($fieldName) || $forceReturnedObject)) {
            if (!$castConstructor = $this->castingHelper($fieldName)) {
                $castConstructor = $this->stat('default_cast');
            }

            $valueObject = \Object::create_from_string($castConstructor, $fieldName);
            $valueObject->setValue($value, $this);

            $value = $valueObject;
        }

        if (!$value instanceof \ViewableData && $forceReturnedObject) {
            $default = \Config::inst()->get('ViewableData', 'default_cast', \Config::FIRST_SET);
            $castedValue = new $default($fieldName);
            $castedValue->setValue($value);
            $value = $castedValue;
        }

        return $value;
    }
} 