<?php

namespace Heystack\Subsystem\Core\State;

interface StateableInterface
{

    public function saveState();
    public function restoreState();

}
