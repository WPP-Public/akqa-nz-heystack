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
     * @var
     */
    protected $logger;
    /**
     *
     */
    public function __construct()
    {
        parent::__construct('Heystack', '2.1.1');
    }
    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }
    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
