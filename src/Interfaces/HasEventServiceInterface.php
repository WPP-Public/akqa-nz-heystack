<?php

namespace Heystack\Core\Interfaces;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class HasDealHandlerInterface
 * @package Heystack\Deals\Interfaces
 */
interface HasEventServiceInterface
{
    /**
     * @return HasEventServiceInterface
     */
    public function getEventService();

    /**
     * @param  HasEventServiceInterface $eventService
     * @return mixed
     */
    public function setEventService(EventDispatcherInterface $eventService);
}
