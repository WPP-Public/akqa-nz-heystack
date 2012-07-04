<?php

namespace Heystack\Subsystem\Core\Output;

use Heystack\Subsystem\Core\Output\ProcessorInterface;

use Heystack\Subsystem\Core\Processor\HandlerTrait;

class Handler
{

    use HandlerTrait;

    public function addProcessor(ProcessorInterface $processor)
    {

        $this->processors[$processor->getIdentifier()] = $processor;

    }

    public function process($identifier, \Controller $controller, $result = null)
    {

        if ($this->hasProcessor($identifier)) {

            return $this->processors[$identifier]->process($controller, $result);

        }

    }

}