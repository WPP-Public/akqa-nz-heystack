<?php

namespace Heystack\Subsystem\Core\Storage;

use Heystack\Subsystem\Core\Storage\ProcessorInterface;

use Heystack\Subsystem\Core\Processor\HandlerTrait;

class Handler
{

    use HandlerTrait;

    public function addProcessor(ProcessorInterface $processor)
    {

        $this->processors[$processor->getName()] = $processor;

    }

}
