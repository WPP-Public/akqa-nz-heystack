<?php

namespace Heystack\Subsystem\Core\Exception;

use Heystack\Subsystem\Core\Services;
use Heystack\Subsystem\Core\ServiceStore;
use Monolog\Logger;

/**
 * Class ConfigurationException
 * @package Heystack\Subsystem\Core\Exception
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

        $message = "Configuration Error: $message";

        if (!defined('UNIT_TESTING')) {

            $monolog = ServiceStore::getService(Services::MONOLOG);

            if ($monolog instanceof Logger) {

                $monolog->err($message);

            }

        }

        parent::__construct($message, $code, $previous);

    }
}
