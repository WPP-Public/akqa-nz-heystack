<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Core\Storage;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

/**
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class Event extends SymfonyEvent
{
    /**
     * @var null
     */
    private $parentReference = null;

    /**
     * @param $parentReference
     */
    public function __construct($parentReference)
    {
        $this->parentReference = $parentReference;
    }

    /**
     * @return null
     */
    public function getParentReference()
    {
        return $this->parentReference;
    }
}
