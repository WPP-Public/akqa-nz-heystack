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

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class HeystackSilverStripeContainerBuilder
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */
class HeystackSilverStripeContainerBuilder extends ContainerBuilder
{
    /**
     * @var \Injector
     */
    protected $injector;

    public function __construct()
    {
        $this->injector = \Injector::inst();
        parent::__construct();
    }

    /**
     * Return true if the service requested is a SilverStripe service and it exists in the SS container
     *
     * @param  string $id
     * @return bool
     */
    public function has($id)
    {
        if (substr($id, 0, 13) === 'silverstripe.') {
            return (bool) $this->injector->get(substr($id, 13));
        } else {
            return parent::has($id);
        }
    }
}
