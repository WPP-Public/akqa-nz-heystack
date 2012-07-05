<?php

namespace Heystack\Subsystem\Core\Processor;

trait HandlerTrait
{

    private $processors = array();

    abstract public function addProcessor();
    abstract public function process();

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
