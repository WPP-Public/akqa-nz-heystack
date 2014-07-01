<?php

namespace Heystack\Core\Interfaces;

use Heystack\Core\EventDispatcher;

/**
 * Interface HasEventServiceInterface
 * @package Heystack\Core\Interfaces
 */
interface HasEventServiceInterface
{
    /**
     * @return \Heystack\Core\EventDispatcher
     */
    public function getEventService();

    /**
     * @param \Heystack\Core\EventDispatcher $eventService
     * @return void
     */
    public function setEventService(EventDispatcher $eventService);
}
