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
     * @param  State $stateService
     * @return mixed
     */
    public function setStateService(State $stateService);

    /**
     * @return mixed
     */
    public function getStateService();
}
