<?php

namespace Heystack\Subsystem\Core\Traits;

use Heystack\Subsystem\Core\State\State;

trait HasStateService
{
    /**
     * @var \Heystack\Subsystem\Core\State\State
     */
    protected $stateService;

    public function setStateService(State $stateService)
    {
        $this->stateService = $stateService;
    }


}