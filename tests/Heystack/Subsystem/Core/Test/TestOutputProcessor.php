<?php

namespace Heystack\Subsystem\Core\Test;

class TestOutputProcessor implements \Heystack\Subsystem\Core\Output\ProcessorInterface
{

    protected $identifier;
    protected $message;

    function __construct($identifier, $message = '')
    {
        $this->identifier = $identifier;
        $this->message = $message;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function process(\Controller $controller, $result = null)
    {
        return $this->message;
    }

}