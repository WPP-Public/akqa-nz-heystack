<?php

namespace Heystack\Core\Input;

use Heystack\Core\Processor\HandlerInterface as ProcessorHandlerInterface;

/**
 * Interface HandlerInterface
 * @package Heystack\Core\Input
 */
interface HandlerInterface extends ProcessorHandlerInterface
{
    /**
     * Adds an input processor to the array of processors, storing it by its identifier
     * @param \Heystack\Core\Input\ProcessorInterface $processor The input processor
     * @return void
     */
    public function addProcessor(ProcessorInterface $processor);

    /**
     * Process an input processor by identifier if it exists
     * @param  string          $identifier The identifier of the input processor
     * @param  \SS_HTTPRequest $request    The request object to process from
     * @return mixed
     */
    public function process($identifier, \SS_HTTPRequest $request);
}
