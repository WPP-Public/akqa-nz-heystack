<?php

namespace Heystack\Core\Interfaces;

use Psr\Log\LoggerInterface;

/**
 * Interface HasLoggerServiceInterface
 * @package Heystack\Core\Interfaces
 */
interface HasLoggerServiceInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @return void
     */
    public function setLoggerService(LoggerInterface $logger);

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLoggerService();
}
