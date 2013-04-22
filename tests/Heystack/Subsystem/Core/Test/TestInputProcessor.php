<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Identifier\Identifier;

/**
 * Class TestInputProcessor
 * @package Heystack\Subsystem\Core\Test
 */
class TestInputProcessor implements \Heystack\Subsystem\Core\Input\ProcessorInterface
{

    /**
     * @var
     */
    protected $identifier;
    /**
     * @var string
     */
    protected $message;

    /**
     * @param        $identifier
     * @param string $message
     */
    public function __construct($identifier, $message = '')
    {
        $this->identifier = $identifier;
        $this->message = $message;
    }
    /**
     * @return \Heystack\Subsystem\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->identifier);
    }
    /**
     * @param \SS_HTTPRequest $request
     * @return mixed|string
     */
    public function process(\SS_HTTPRequest $request)
    {
        return $this->message;
    }
}
