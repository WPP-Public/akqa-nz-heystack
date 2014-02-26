<?php

namespace Heystack\Core\Traits;

use Heystack\Core\State\State;

/**
 * Class HasStateService
 * @package Heystack\Core\Traits
 */
trait HasStateServiceTrait
{
    /**
     * @var \Heystack\Core\State\State
     */
    protected $stateService;

    /**
     * @param State $stateService
     */
    public function setStateService(State $stateService)
    {
        $this->stateService = $stateService;
    }

    /**
     * @return State
     */
    public function getStateService()
    {
        return $this->stateService;
    }
}
