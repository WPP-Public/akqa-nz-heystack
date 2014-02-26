<?php

namespace Heystack\Core\Test;

use Heystack\Core\ViewableData\ViewableDataInterface;

class TestViewableData implements ViewableDataInterface
{

    protected $dynamicMethods = [];
    protected $castings = [];

    public function __construct($data = [], $castings = [])
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
