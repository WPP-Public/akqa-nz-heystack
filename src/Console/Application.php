<?php

namespace Heystack\Core\Console;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * The command-line application for heystack
 * 
 * This application makes commands available to the user through the DI system
 * 
 * @package Heystack\Core\Console
 */
class Application extends BaseApplication
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct('Heystack', '3.0.0');
    }
}
