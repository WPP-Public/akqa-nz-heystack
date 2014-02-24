<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Input namespace
 */
namespace Heystack\Core\Input;

use Heystack\Core\Identifier\Identifier;
use Heystack\Core\Input\ProcessorInterface;
use Heystack\Core\Processor\HandlerTrait;

/**
 * Allows a group/array processors to be run from a single identifier
 *
 * Enables the ability to trigger multiple intput processors from one request
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
     * Adds an input processor to the array of processors, storing it by its identifier
     * @param ProcessorInterface $processor The input processor
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
     * @param \SS_HTTPRequest $request The request object to pass into processors
     */
    public function process(\SS_HTTPRequest $request)
    {
        $results = [];

        foreach ($this->processors as $identifier => $processor) {

            $results[$identifier] = $processor->process($request);

        }

        return $results;
    }
}
