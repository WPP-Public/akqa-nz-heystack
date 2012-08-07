<?php

namespace Heystack\Subsystem\Core\ViewableData;

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
