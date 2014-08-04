<?php

namespace Heystack\Core\Traits;

use Heystack\Core\State\State;

/**
 * Allows a using class to set a state service
 * @package Heystack\Core\Traits
 */
trait HasStateServiceTrait
{
    /**
     * @var \Heystack\Core\State\State
     */
    protected $stateService;

    /**
     * @param \Heystack\Core\State\State $stateService
     * @return void
     */
    public function setStateService(State $stateService)
    {
        $this->stateService = $stateService;
    }

    /**
     * @return \Heystack\Core\State\State
     */
    public function getStateService()
    {
        return $this->stateService;
    }
}
