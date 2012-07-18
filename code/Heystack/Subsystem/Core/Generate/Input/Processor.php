<?php

namespace Heystack\Subsystem\Core\Generate\Input;

use Heystack\Subsystem\Core\Input\ProcessorInterface;
use Heystack\Subsystem\Core\Generate\DataObjectGenerator;

class Processor implements ProcessorInterface
{

    private $generatorService;

    public function __construct(DataObjectGenerator $generatorService)
    {

        $this->generatorService = $generatorService;

    }

    public function getIdentifier()
    {

        return 'god';

    }

    public function process(\SS_HTTPRequest $request)
    {

        $this->generatorService->process();

    }

}
