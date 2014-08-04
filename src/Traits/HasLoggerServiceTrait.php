<?php

namespace Heystack\Core\Traits;

use Psr\Log\LoggerInterface;

/**
 * Allows a using class to set a logger service
 * @package Heystack\Core\Traits
 */
trait HasLoggerServiceTrait
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $loggerService;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @return void
     */
    public function setLoggerService(LoggerInterface $logger)
    {
        $this->loggerService = $logger;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLoggerService()
    {
        return $this->loggerService;
    }
}
