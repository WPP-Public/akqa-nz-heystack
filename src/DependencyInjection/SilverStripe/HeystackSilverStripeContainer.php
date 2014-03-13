<?php

namespace Heystack\Core\DependencyInjection\SilverStripe;

use Symfony\Component\DependencyInjection\Container;

/**
 * Provides a base class for the generated heystack container, to bridge with SilverStripe Injection
 * 
 * This class allows Symfony dependency injection to access services from the SilverStripe
 * Injection system.
 * 
 * Symfony lowercases all service requests, so a parameter "silverstripe_service_mapping"
 * can be set that will provide a mapping between "heystack requested" => "SilverStripe provided" services
 * 
 * For example, if there is a service called "Logger" in the SilverStripe injection system,
 * and it is requested in the Symfony system like so:
 * 
 * services:
 *   my_service:
 *     arguments: [ @silverstripe.logger ]
 * 
 * Then you can allow the service to be accessed via its upper-case name by setting:
 * 
 * parameters:
 *   silverstripe_service_mapping:
 *     logger: Logger
 * 
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */
class HeystackSilverStripeContainer extends Container
{
    use SilverStripeServiceTrait;

    /**
     * Use SilverStripe's Dependency Injection system if the service is namespaced silverstripe
     *
     * @param  string $id
     * @param  int    $invalidBehavior
     * @return object
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        if ($this->isSilverStripeServiceRequest($id)) {
            return $this->getSilverStripeService($id);
        } else {
            return parent::get($id, $invalidBehavior);
        }
    }
}
