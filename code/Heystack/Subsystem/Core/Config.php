<?php

/**
 * This file is part of the Heystack package
 * 
 * @package Heystack
 */

/**
 * Core namespace
 */
namespace Heystack\Subsystem\Core;

/**
 * Provides global configuration storage.
 * 
 * Config is a globally accessible configuration class, 
 * @package Heystack
 */
class Config
{

    private static $containerExtensions;

    public static function registerContainerExtension($extension)
    {

        self::$containerExtensions[] = $extension;

    }

    public static function getContainerExtensions()
    {

        return self::$containerExtensions;

    }

}
