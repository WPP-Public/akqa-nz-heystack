<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 *
 * Alows regular objects that implement ViewableDataInterface to be used in templates
 * 
 * @copyright  Heyday
 * @author Cameron Spiers <cameron@heyday.co.nz>
 * @author  Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 *
 */
class ViewableDataFormatter extends ViewableData
{

    protected $obj;

    public function __construct(\Heystack\Subsystem\Core\ViewableDataInterface $obj)
    {
        $this->obj = $obj;

        parent::__construct();

    }

    public function castingHelper($field)
    {

        $castings = $this->obj->getCastings();

        if (isset($castings[$field])) {

            return $castings[$field];

        } else {

            return parent::castingHelper($field);
        }

    }

    public function __call($method, $arguments)
    {
        if (method_exists($this->obj, 'get' . $method)) {
            return call_user_func_array(array($this->obj, 'get' . $method), $arguments);
        } elseif (in_array($method, $this->obj->getDynamicMethods())) {
            return $this->obj->$method;
        }
    }

    public function __get($property)
    {
        if (isset($this->obj->$property)) {
            return $this->obj->$property;
        }
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function hasMethod($method)
    {
        return method_exists($this->obj, 'get' . $method) || in_array($method, $this->obj->getDynamicMethods());
    }

}
