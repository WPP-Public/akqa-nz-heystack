<?php

namespace Heystack\Subsystem\Processor\Output;

use Heystack\Subsystem\Core\Output\ProcessorInterface;

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

    public function process(\Controller $controller, $result = null)
    {
        
        foreach ($this->processors as $identifier => $processor) {
            
            $processor->process($controller, $result);
            
        }
        
        return $controller->getResponse();

    }

}