<?php

namespace Heystack\Core\Traits;

use Heystack\Core\EventDispatcher;

/**
 * Allows a using class to set a event service
 * @package Heystack\Core\Traits
 */
trait HasEventServiceTrait
{
    /**
     * @var \Heystack\Core\EventDispatcher
     */
    protected $eventService;

    /**
     * @param \Heystack\Core\EventDispatcher $eventService
     */
    public function setEventService(EventDispatcher $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @return \Heystack\Core\EventDispatcher
     */
    public function getEventService()
    {
        return $this->eventService;
    }
}
