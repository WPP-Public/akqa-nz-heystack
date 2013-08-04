<?php

namespace Heystack\Subsystem\Core\Interfaces;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class HasDealHandlerInterface
 * @package Heystack\Subsystem\Deals\Interfaces
 */
interface HasEventServiceInterface
{
    /**
     * @return HasEventServiceInterface
     */
    public function getEventService();

    /**
     * @param HasEventServiceInterface $eventService
     * @return mixed
     */
    public function setEventService(EventDispatcherInterface $eventService);
}