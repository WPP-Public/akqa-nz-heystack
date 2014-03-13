<?php

namespace Heystack\Core\DependencyInjection\SilverStripe;

use Symfony\Component\DependencyInjection\Container;

/**
 * Meant to replace SilverStripe's Injection Creator and allows services in the generated container
 * (Using Symfony's Dependency Injection) to be used in SilverStripe's Dependency Injection
 * 
 * This class also allows the usage of parameters from the heystack container in SilverStripe injection
 * 
 * When a requested service of parameter does not begin with "heystack." the the implementation
 * will default to using the standard SilverStripe InjectionCreator
 * 
 * But if a service is request with "heystack." as a prefix but the service doesn't exist in the 
 * heystack container, then the service will throw and exception
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */
class HeystackInjectionCreator extends \InjectionCreator
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $heystackContainer;

    /**
     * @param Container $heystackContainer the generated container
     */
    public function __construct(Container $heystackContainer)
    {
        $this->heystackContainer = $heystackContainer;
    }

    /**
     * @param string $service
     * @param array $params
     * @return mixed|object
     * @throws \InvalidArgumentException
     */
    public function create($service, array $params = array())
    {
        if (substr($service, 0, 9) === 'heystack.') {
            $service = substr($service, 9);
            if ($this->heystackContainer->has($service)) {
                return $this->heystackContainer->get($service);
            } elseif ($this->heystackContainer->hasParameter($service)) {
                return $this->heystackContainer->getParameter($service);
            } else {
                throw new \InvalidArgumentException(
                    sprintf(
                        "Requested Heystack service or parameter '%s' doesn't exist",
                        $service
                    )
                );
            }
        } else {
            return parent::create($service, $params);
        }
    }
}
