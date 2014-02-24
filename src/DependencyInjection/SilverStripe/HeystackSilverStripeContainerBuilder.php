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
     * Always return true if the service being queried is namespaced silverstripe
     *
     * @param  string $id
     * @return bool
     */
    public function has($id)
    {
        if (substr($id, 0, 13) === 'silverstripe.') {
            return true;
        } else {
            return parent::has($id);
        }
    }
}
