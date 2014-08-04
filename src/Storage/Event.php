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
     * @var mixed
     */
    private $parentReference = null;

    /**
     * @param mixed $parentReference
     */
    public function __construct($parentReference)
    {
        $this->parentReference = $parentReference;
    }

    /**
     * @return mixed
     */
    public function getParentReference()
    {
        return $this->parentReference;
    }
}
