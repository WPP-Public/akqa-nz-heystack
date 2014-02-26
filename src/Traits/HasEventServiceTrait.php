<?php

namespace Heystack\Core\Traits;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class HasEventService
 * @package Heystack\Core\Traits
 */
trait HasEventServiceTrait
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventService;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventService
     */
    public function setEventService(EventDispatcherInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public function getEventService()
    {
        return $this->eventService;
    }
}
