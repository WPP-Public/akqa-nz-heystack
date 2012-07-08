<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Input namespace
 */
namespace Heystack\Subsystem\Core\Input;

use Heystack\Subsystem\Core\Input\ProcessorInterface;

use Heystack\Subsystem\Core\Processor\HandlerTrait;

/**
 * Holder/handlers and stores input processors.
 *
 * This class provides storage and processing  of input processors,
 * processors are stored and accessed by an identifier
 * @package Heystack
 */
class Handler
{
    /**
     * Use the handler trait
     */
    use HandlerTrait;

    /**
     * Adds an input processor to the array of processors, storing it by its identifier
     * @param ProcessorInterface $processor The input processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {

        $this->processors[$processor->getIdentifier()] = $processor;

    }

    /**
     * Process an input processors by identifier if it exists
     * @param  string          $identifier The identifier of the input processor
     * @param  \SS_HTTPRequest $request    The request object to process from
     * @return mixed
     */
    public function process($identifier, \SS_HTTPRequest $request)
    {

        if ($this->hasProcessor($identifier)) {

            return $this->processors[$identifier]->process($request);

        } else {

            return false;

        }

    }

}
