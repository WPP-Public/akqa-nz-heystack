<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\ViewableData\ViewableDataInterface;

class TestViewableData implements ViewableDataInterface
{

    protected $dynamicMethods = array();
    protected $castings = array();

    public function __construct($data = array(), $castings = array())
    {
        foreach ($data as $key => $value) {

            $this->$key = $value;

        }

        $this->dynamicMethods = array_keys($data);
        $this->castings = $castings;
    }

    /**
     * Defines what methods the implementing class implements dynamically through __get and __set
     */
    public function getDynamicMethods()
    {
        return $this->dynamicMethods;
    }

    /**
     * Returns an array of SilverStripe DBField castings keyed by field name
     */
    public function getCastings()
    {
        return $this->castings;
    }

    public function getTestThing()
    {
        return 'Awesome';
    }

}
