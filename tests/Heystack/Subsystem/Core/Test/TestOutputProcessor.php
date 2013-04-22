<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Identifier\Identifier;

/**
 * Class TestOutputProcessor
 * @package Heystack\Subsystem\Core\Test
 */
class TestOutputProcessor implements \Heystack\Subsystem\Core\Output\ProcessorInterface
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
     * @return Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->identifier);
    }

    /**
     * @param \Controller $controller
     * @param null        $result
     * @return string
     */
    public function process(\Controller $controller, $result = null)
    {
        return $this->message;
    }
}
