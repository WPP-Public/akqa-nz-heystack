<?php

namespace Heystack\Subsystem\Core\Traits;
use Symfony\Component\EventDispatcher\EventDispatcher;

trait HasEventService
{
    /**
     * @var \Heystack\Subsystem\Deals\Interfaces\DealHandlerInterface
     */
    protected $eventService;

    public function setEventService(EventDispatcher $eventService)
    {
        $this->$eventService = $eventService;
    }

    /**
     * @return \Heystack\Subsystem\Deals\Interfaces\DealHandlerInterface
     */
    public function getEventService()
    {
        return $this->eventService;
    }
}