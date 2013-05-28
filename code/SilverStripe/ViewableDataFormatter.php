<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

use Heystack\Subsystem\Core\ViewableData\ViewableDataInterface;
/**
 * Allows objects that implement \Heystack\Subsystem\Core\ViewableDataInterface
 * to be used in SS templates.
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 *
 */
class ViewableDataFormatter extends \ViewableData
{
    /**
     * @var Heystack\Subsystem\Core\ViewableData\ViewableDataInterface
     */
    protected $obj;
    /**
     * @param ViewableDataInterface $obj
     */
    public function __construct(ViewableDataInterface $obj)
    {
        $this->obj = $obj;

        parent::__construct();

    }
    /**
     * @param string $field
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
     * @param string $method
     * @param array  $arguments
     * @return bool|mixed
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->obj, 'get' . $method)) {
            return call_user_func_array(array($this->obj, 'get' . $method), $arguments);
        } elseif (in_array($method, $this->obj->getDynamicMethods())) {
            return $this->obj->$method;
        }

        return false;
    }
    /**
     * @param string $property
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
     * @param string $method
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
