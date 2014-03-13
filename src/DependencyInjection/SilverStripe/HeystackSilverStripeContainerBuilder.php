<?php

namespace Heystack\Core\DependencyInjection\SilverStripe;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Allows the Symfony container to be built even if accessing SilverStripe services
 * 
 * This class is used when building the Symfony container to ensure that
 * requests for SilverStripe services do not cause errors
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */
class HeystackSilverStripeContainerBuilder extends ContainerBuilder
{
    use SilverStripeServiceTrait;

    /**
     * Return true if the service requested is a SilverStripe service and it exists in the SS container
     *
     * @param  string $id
     * @return bool
     */
    public function has($id)
    {
        if ($this->isSilverStripeServiceRequest($id)) {
            return (bool) $this->getSilverStripeService($id);
        } else {
            return parent::has($id);
        }
    }
}
