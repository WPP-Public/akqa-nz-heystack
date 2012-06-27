<?php

namespace Ecommerce\Subsystem\Core;

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
