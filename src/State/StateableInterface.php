<?php

namespace Heystack\Core\State;

/**
 * The interface to be implemented for things that save and restore state
 * @package Heystack\Core\State
 */
interface StateableInterface
{
    /**
     * @return void
     */
    public function saveState();

    /**
     * @return void
     */
    public function restoreState();
}
