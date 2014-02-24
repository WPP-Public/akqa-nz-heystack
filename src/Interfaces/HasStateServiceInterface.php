<?php

namespace Heystack\Core\Interfaces;

use Heystack\Core\State\State;

/**
 * Class HasStateServiceInterface
 * @package Heystack\Core\Interfaces
 */
interface HasStateServiceInterface
{

    /**
     * @param  HasStateServiceInterface $eventService
     * @return mixed
     */
    public function setStateService(State $stateService);
    public function getStateService();
}
