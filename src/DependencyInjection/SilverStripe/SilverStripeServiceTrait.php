<?php

namespace Heystack\Core\DependencyInjection\SilverStripe;

/**
 * Provides shared function for accessing the SilverStripe injection and services
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
trait SilverStripeServiceTrait
{
    /**
     * @var \Injector
     */
    protected $injector;

    /**
     * Sets the injector instance
     *
     * @param \Injector $injector
     * @return void
     */
    public function setInjector(\Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * Retrieves the Injector instance
     *
     * @return \Injector
     */
    protected function getInjector()
    {
        if ($this->injector === null) {
            $this->injector = \Injector::inst();
        }

        return $this->injector;
    }

    /**
     * @param string $id
     * @return bool
     */
    protected function isSilverStripeServiceRequest($id)
    {
        return substr($id, 0, 13) === 'silverstripe.';
    }

    /**
     * @param string $id
     * @return object
     */
    protected function getSilverStripeService($id)
    {
        $id = substr($id, 13);
        if ($mapping = $this->getParameter('silverstripe_service_mapping')) {
            if (isset($mapping[$id])) {
                $id = $mapping[$id];
            }
        }
        return $this->getInjector()->get($id);
    }

    /**
     * @param string $name
     * @return mixed
     */
    abstract public function getParameter($name);
} 