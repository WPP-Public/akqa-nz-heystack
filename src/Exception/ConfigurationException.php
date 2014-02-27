<?php

namespace Heystack\Core\Exception;

use Heystack\Core\Services;
use Heystack\Core\ServiceStore;
use Monolog\Logger;

/**
 * Class ConfigurationException
 * @package Heystack\Core\Exception
 */
class ConfigurationException extends \Exception
{
    /**
     * @param string $message
     * @param null   $code
     * @param null   $previous
     */
    public function __construct($message, $code = null, $previous = null)
    {
        parent::__construct("Configuration Error: $message", $code, $previous);
    }
}
