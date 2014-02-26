<?php

namespace Heystack\Core\Traits;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class HasEventService
 * @package Heystack\Core\Traits
 */
trait HasEventService
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventService;

    /**
     * @param EventDispatcherInterface $eventService
     */
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
