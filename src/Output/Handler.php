<?php

namespace Heystack\Core\Output;

use Heystack\Core\Processor\HandlerTrait;

/**
 * Holds and handlers output processors.
 *
 * This class provides storage and processing  of input processors,
 * processors are stored and accessed by an identifier
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @author  Stevie Mayhew <stevie@heyday.co.nz>
 * @author  Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */
class Handler implements HandlerInterface
{
    /**
     * Use the handler trait
     */
    use HandlerTrait;

    /**
     * Adds an output processor to the array of processors, storing it by its identifier
     * @param \Heystack\Core\Output\ProcessorInterface $processor The input processor
     * @return void
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[$processor->getIdentifier()->getFull()] = $processor;
    }

    /**
     * Process an output processor by identifier if it exists
     * @param  string      $identifier The identifier of the processor to run
     * @param  \Controller $controller The controller that handled the request
     * @param  mixed       $result     The result from the run input processor
     * @return mixed|null
     */
    public function process($identifier, \Controller $controller, $result = null)
    {
        if ($this->hasProcessor($identifier)) {
            return $this->processors[$identifier]->process($controller, $result);
        } else {
            return false;
        }
    }
}
