<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * ViewableData namespace
 */
namespace Heystack\Core\ViewableData;

/**
 * Allows objects that implement \Heystack\Core\ViewableDataInterface
 * to be used in SS templates.
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 *
 */
class ViewableDataFormatter extends \ViewableData implements \ArrayAccess
{
    /**
     * @var \Heystack\Core\ViewableData\ViewableDataInterface
     */
    protected $obj;

    /**
     * Implements ArrayAccess for use in DBFields when casting.
     * No functionality is required
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return false;
    }

    /**
     * Implements ArrayAccess for use in DBFields when casting.
     * No functionality is required
     * @param  mixed      $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return null;
    }

    /**
     * Implements ArrayAccess for use in DBFields when casting.
     * No functionality is required
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        //Do nothing
    }

    /**
     * Implements ArrayAccess for use in DBFields when casting.
     * No functionality is required
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        //Do nothing
    }

    /**
     * @param ViewableDataInterface $obj
     */
    public function __construct(ViewableDataInterface $obj)
    {
        $this->obj = $obj;

        parent::__construct();

    }

    /**
     * @param  string $field
     * @return string
     */
    public function castingHelper($field)
    {
        $castings = $this->obj->getCastings();
        if (isset($castings[$field])) {
            return $castings[$field];
        } else {
            return parent::castingHelper($field);
        }
    }

    /**
     * @param  string     $method
     * @param  array      $arguments
     * @return bool|mixed
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->obj, 'get' . $method)) {
            return call_user_func_array([$this->obj, 'get' . $method], $arguments);
        } elseif (in_array($method, $this->obj->getDynamicMethods())) {
            return $this->obj->$method;
        }

        return false;
    }

    /**
     * @param  string $property
     * @return bool
     */
    public function __get($property)
    {
        if (isset($this->obj->$property)) {
            return $this->obj->$property;
        }

        return false;
    }

    /**
     * @param string $property
     * @param mixed  $value
     */
    public function __set($property, $value)
    {
        $this->obj->$property = $value;
    }

    /**
     * @param  string $method
     * @return bool
     */
    public function hasMethod($method)
    {
        return method_exists($this->obj, 'get' . $method) || in_array($method, $this->obj->getDynamicMethods());
    }

    /**
     * @return ViewableDataInterface
     */
    public function getObj()
    {
        return $this->obj;
    }
}
