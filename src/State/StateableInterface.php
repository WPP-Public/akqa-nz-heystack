<?php

namespace Heystack\Core\State;

/**
 * Class StateableInterface
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
