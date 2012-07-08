<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Output namespace
 */
namespace Heystack\Subsystem\Processor\Output;

use Heystack\Subsystem\Core\Output\ProcessorInterface;

/**
 * Allows a group/array processors to be run from a single identifier
 *
 * Enables the ability to trigger multiple output processors from one request
 * 
 * @package Heystack
 */
class GroupedProcessor implements ProcessorInterface
{

    use Heystack\Subsystem\Core\Processor\HandlerTrait;

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
    public function __construct($identifier, array $processors)
    {

        $this->identifier = $identifier;
        $this->setProcessors($processors);

    }

    /**
     * Returns the identifier of this processor
     * @return [type] [description]
     */
    public function getIdentifier()
    {

        return $this->identifier;

    }

    /**
     * Runs over the list of processors running them all in turn
     * @param  \Controller $controller The controller the request was handled by
     * @param  mixed      $result     The result from the previosly run input processor/s
     * @return \SS_HTTPResponse                  Controller response
     */
    public function process(\Controller $controller, $result = null)
    {

        foreach ($this->processors as $identifier => $processor) {

            $processor->process($controller, $result);

        }

        return $controller->getResponse();

    }

}
