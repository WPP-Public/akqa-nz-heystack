<?php

namespace Heystack\Subsystem\Core\Processor;

use Heystack\Subsystem\Core\Processor\ProcessorInterface;

class Handler
{

    private $processors = array();

    public function addProcessor(ProcessorInterface $processor)
    {

        $this->processors[$processor->getName()] = $processor;

    }

    public function getProcessor($name)
    {

        return isset($this->processors[$name]) ? $this->processors[$name] : false;

    }

    public function hasProcessor($name)
    {

        return isset($this->processors[$name]);

    }

    public function getProcessors()
    {

        return $this->processors;

    }

    public function setProcessors(array $processors)
    {

        $this->processors = array();

        foreach ($processors as $processor) {

            $this->addProcessor($processor);

        }

    }

}
