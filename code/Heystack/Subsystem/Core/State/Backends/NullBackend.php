<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Backends namespace
 */
namespace Heystack\Subsystem\Core\State\Backends;

use Heystack\Subsystem\Core\State\BackendInterface;

/**
 * Null backend storage
 */
class NullBackend implements BackendInterface
{

    const TRACKING_KEY = 'null.tracking';

    private $keys = false;
    private $prefix = '';

    public function __construct()
    {


    }

    public function getKeys()
    {
        return null;
    }

    public function addKey($key)
    {
        return null;
    }

    public function setByKey($key, $var)
    {
        return null;
    }

    public function getByKey($key)
    {
        return null;
    }

    public function removeByKey($key)
    {
        return null;
    }

    public function removeAll(array $exclude = array())
    {
        return null;
    }

    protected function key($key)
    {
        return null;
    }

}
