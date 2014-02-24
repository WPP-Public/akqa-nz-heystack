<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Output namespace
 */
namespace Heystack\Core\Output;

use Heystack\Core\Identifier\Identifier;
use Heystack\Core\Output\ProcessorInterface;
use Heystack\Core\Processor\HandlerTrait;

/**
 * Allows a group/array processors to be run from a single identifier
 *
 * Enables the ability to trigger multiple output processors from one request
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @author  Stevie Mayhew <stevie@heyday.co.nz>
 * @author  Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */
class GroupedProcessor implements ProcessorInterface
{

    use HandlerTrait;

    /**
     * Identifier of the grouped processor
     * @var string
     */
    private $identifier;
    /**
     * Runs when the object is instantiated and sees the processors and identifier
     * @param string $identifier Identifier for the group of processors
     * @param array  $processors Array of processors
     */
    public function __construct($identifier, $processors = null)
    {

        $this->identifier = $identifier;

        if (is_array($processors)) {

            $this->setProcessors($processors);

        }

    }
    /**
     * Adds an output processor to the array of processors, storing it by its identifier
     * @param ProcessorInterface $processor The output processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[$processor->getIdentifier()->getFull()] = $processor;
    }
    /**
     * Returns the identifier of this processor
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->identifier);
    }

    /**
     * Runs over the list of processors running them all in turn
     * @param  \Controller      $controller The controller the request was handled by
     * @param  mixed            $result     The result from the previosly run input processor/s
     * @return \SS_HTTPResponse Controller response
     */
    public function process(\Controller $controller, $result = null)
    {
        foreach ($this->processors as $processor) {
            $processor->process($controller, $result);
        }

        return $controller->getResponse();
    }
}
