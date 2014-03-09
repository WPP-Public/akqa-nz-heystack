<?php

namespace Heystack\Core\Storage\Interfaces;

/**
 * Interface ParentReferenceInterface
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