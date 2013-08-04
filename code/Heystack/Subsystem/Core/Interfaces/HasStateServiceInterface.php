<?php

namespace Heystack\Subsystem\Core\Interfaces;

use Heystack\Subsystem\Core\State\State;

/**
 * Class HasStateServiceInterface
 * @package Heystack\Subsystem\Core\Interfaces
 */
interface HasStateServiceInterface
{

    /**
     * @param HasStateServiceInterface $eventService
     * @return mixed
     */
    public function setStateService(State $eventService);
}