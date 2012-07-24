<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Input\Generate namespace
 */
namespace Heystack\Subsystem\Core\Generate\Input;

use Heystack\Subsystem\Core\Input\ProcessorInterface;
use Heystack\Subsystem\Core\Generate\DataObjectGenerator;

/**
 * Kicks off the generator service from a cli controller
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
class Processor implements ProcessorInterface
{

    private $generatorService;

    public function __construct(DataObjectGenerator $generatorService)
    {

        $this->generatorService = $generatorService;

    }

    public function getIdentifier()
    {

        return 'do_generator';

    }

    public function process(\SS_HTTPRequest $request)
    {

        $this->generatorService->process($request->getVar('force'));

    }

}
