<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

/**
 * Class HasLoggerService
 * @package Heystack\Core\DependencyInjection\CompilerPass
 */
class HasLoggerService extends HasService
{
    /**
     * The name of the service in the container
     * @return string
     */
    protected function getServiceName()
    {
        return 'logger';
    }

    /**
     * The method name used to set the service
     * @return string
     */
    protected function getServiceSetterName()
    {
        return 'setLogger';
    }
}
