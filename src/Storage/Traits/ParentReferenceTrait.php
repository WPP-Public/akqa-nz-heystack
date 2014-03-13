<?php

namespace Heystack\Core\Storage\Traits;

/**
 * Allows a using class to set a parent reference
 * @package Heystack\Core\Storage\Traits
 */
trait ParentReferenceTrait
{
    /**
     * @var mixed
     */
    protected $parentReference;

    /**
     * @return mixed
     */
    public function getParentReference()
    {
        return $this->parentReference;
    }

    /**
     * @param mixed $reference
     */
    public function setParentReference($reference)
    {
        $this->parentReference = $reference;
    }
}
