<?php

namespace Heystack\Core\Console;

use Monolog\Logger;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
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
