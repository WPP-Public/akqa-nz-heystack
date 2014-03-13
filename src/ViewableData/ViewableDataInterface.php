<?php

namespace Heystack\Core\ViewableData;

/**
 * Implementing class can be wrapped in a ViewableDataFormattor and then used in templates
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @author  Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */
interface ViewableDataInterface
{
    /**
     * Defines what methods the implementing class implements dynamically through __get and __set
     */
    public function getDynamicMethods();

    /**
     * Returns an array of SilverStripe DBField castings keyed by field name
     */
    public function getCastings();
}
