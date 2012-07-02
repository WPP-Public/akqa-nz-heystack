<?php

namespace Heystack\Subsystem\Core\Output;

use Heystack\Subsystem\Core\Output\ProcessorInterface;

use Heystack\Subsystem\Core\Processor\HandlerTrait;

class Handler
{

    use HandlerTrait;

    public function addProcessor(ProcessorInterface $processor)
    {

        $this->processors[$processor->getName()] = $processor;

    }

    public function process($name, $request)
    {

        if ($this->hasProcessor($name)) {

            return $this->processors[$name]->process($request);

        }

    }

}