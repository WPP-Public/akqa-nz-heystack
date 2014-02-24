<?php

namespace Heystack\Core\Traits;

use Heystack\Core\State\State;

trait HasStateService
{
    /**
     * @var \Heystack\Core\State\State
     */
    protected $stateService;

    public function setStateService(State $stateService)
    {
        $this->stateService = $stateService;
    }

}
