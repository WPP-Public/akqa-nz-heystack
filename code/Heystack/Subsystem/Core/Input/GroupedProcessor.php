<?php

namespace Heystack\Subsystem\Processor\Input;

use Heystack\Subsystem\Core\Input\ProcessorInterface;

class GroupedProcessor implements ProcessorInterface
{

    use Heystack\Subsystem\Core\Processor\HandlerTrait;

    private $identifier;

    public function __construct($identifier, array $processors)
    {

        $this->identifier = $identifier;
        $this->setProcessors($processors);

    }

    public function getIdentifier()
    {

        return $this->identifier;

    }

    public function process(\SS_HTTPRequest $request)
    {

        $results = array();

        foreach ($this->processors as $identifier => $processor) {

            $results[$identifier] = $processor->process($request);

        }

        return $results;

    }

}
