<?php

namespace Heystack\Core\Interfaces;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Interface HasEventServiceInterface
 * @package Heystack\Core\Interfaces
 */
interface HasEventServiceInterface
{
    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public function getEventService();

    /**
     * @param  \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventService
     * @return void
     */
    public function setEventService(EventDispatcherInterface $eventService);
}
