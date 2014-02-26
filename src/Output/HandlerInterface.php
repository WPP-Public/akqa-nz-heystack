<?php

namespace Heystack\Core\Output;

use Heystack\Core\Processor\HandlerInterface as ProcessorHandlerInterface;

/**
 * Interface HandlerInterface
 * @package Heystack\Core\Output
 */
interface HandlerInterface extends ProcessorHandlerInterface
{

    /**
     * Adds an output processor to the array of processors, storing it by its identifier
     * @param ProcessorInterface $processor The input processor
     */
    public function addProcessor(ProcessorInterface $processor);

    /**
     * Process an output processor by identifier if it exists
     * @param  string      $identifier The identifier of the processor to run
     * @param  \Controller $controller The controller that handled the request
     * @param  mixed       $result     The result from the run input processor
     * @return mixed|null
     */
    public function process($identifier, \Controller $controller, $result = null);
}
