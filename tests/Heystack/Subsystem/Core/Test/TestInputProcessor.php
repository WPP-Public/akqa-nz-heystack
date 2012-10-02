<?php

namespace Heystack\Subsystem\Core\Test;

class TestInputProcessor implements \Heystack\Subsystem\Core\Input\ProcessorInterface
{

    protected $identifier;
    protected $message;

    public function __construct($identifier, $message = '')
    {
        $this->identifier = $identifier;
        $this->message = $message;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function process(\SS_HTTPRequest $request)
    {
        return $this->message;
    }

}
