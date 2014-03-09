<?php

namespace Heystack\Core\Storage\Traits;

/**
 * Class ParentReferenceTrait
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
