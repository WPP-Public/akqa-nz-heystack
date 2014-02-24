<?php

namespace Heystack\Core\State;

/**
 * Class StateableInterface
 * @package Heystack\Core\State
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
