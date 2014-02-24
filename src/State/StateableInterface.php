<?php

namespace Heystack\Subsystem\Core\State;

/**
 * Class StateableInterface
 * @package Heystack\Subsystem\Core\State
 */
interface StateableInterface
{
    /**
     * @return mixed
     */
    public function saveState();

    /**
     * @return mixed
     */
    public function restoreState();
}
