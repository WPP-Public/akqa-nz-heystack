<?php

namespace Heystack\Core\Input;

/**
 * Interface for input processors
 *
 * Input processors need to implement this interface in order to be added to a processors handler.
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
interface ProcessorInterface
{

    /**
     * Returns the identifier of the processor
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier();

    /**
     * Executes the main functionality of the input processor
     * @param  \SS_HTTPRequest $request Request to process
     * @return mixed
     */
    public function process(\SS_HTTPRequest $request);
}
