<?php

namespace Heystack\Subsystem\Core\Console;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('Heystack', '1.1');
    }
}