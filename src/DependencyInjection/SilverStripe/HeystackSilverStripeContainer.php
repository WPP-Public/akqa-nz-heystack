<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * DependencyInjection namespace
 */
namespace Heystack\Core\DependencyInjection\SilverStripe;

use Symfony\Component\DependencyInjection\Container;

/**
 * Class HeystackSilverStripeContainer
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */
class HeystackSilverStripeContainer extends Container
{
    protected $injector;

    /**
     * Sets the injector instance
     *
     * @param \Injector $injector
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
     * Use SilverStripe's Dependency Injection system if the service is namespaced silverstripe
     *
     * @param  string $id
     * @param  int    $invalidBehavior
     * @return object
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        if (substr($id, 0, 13) === 'silverstripe.') {
            return $this->getInjector()->get(substr($id, 13));
        } else {
            return parent::get($id, $invalidBehavior);
        }
    }
}
