<?php

namespace Heystack\Core\Traits;

use Psr\Log\LoggerInterface;

/**
 * Class HasLoggerServiceTrait
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
