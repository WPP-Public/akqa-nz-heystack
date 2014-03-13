<?php

namespace Heystack\Core\Storage\Interfaces;

/**
 * The interface the needs to be implemented if you need a class to have a parent reference set
 * 
 * Used in combination with the ParentReferenceTrait
 * 
 * @package Heystack\Core\Storage\Interfaces
 */
interface ParentReferenceInterface
{
    /**
     * @return mixed
     */
    public function getParentReference();

    /**
     * @param $reference
     */
    public function setParentReference($reference);
} 