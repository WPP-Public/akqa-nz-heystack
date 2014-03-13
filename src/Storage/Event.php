<?php

namespace Heystack\Core\Storage;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

/**
 * Allows a parent reference to be set onto an event
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
