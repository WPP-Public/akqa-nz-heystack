<?php

namespace Heystack\Subsystem\Core\Input;

use Heystack\Subsystem\Core\Input\ProcessorInterface;

use Heystack\Subsystem\Core\Processor\HandlerTrait;

class Handler
{

    use HandlerTrait;

    public function addProcessor(ProcessorInterface $processor)
    {

        $this->processors[$processor->getIdentifier()] = $processor;

    }

    public function process($identifier, $request)
    {

        if ($this->hasProcessor($identifier)) {

            return $this->processors[$identifier]->process($request);

        } else {

            return false;

        }

    }

}