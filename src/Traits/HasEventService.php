<?php

namespace Heystack\Subsystem\Core\Traits;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait HasEventService
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventService;

    public function setEventService(EventDispatcherInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function getEventService()
    {
        return $this->eventService;
    }
}
