<?php

namespace Heystack\Subsystem\Core\Storage\Traits;

trait ParentReferenceTrait
{

    protected $parentReference;

    public function getParentReference()
    {
        return $this->parentReference;
    }

    public function setParentReference($reference)
    {
        $this->parentReference = $reference;
    }

}
