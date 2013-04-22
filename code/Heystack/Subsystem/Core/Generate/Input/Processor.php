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

use Heystack\Subsystem\Core\Identifier\Identifier;
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

    /**
     *
     */
    const IDENTIFIER = 'do_generator';

    /**
     * @var \Heystack\Subsystem\Core\Generate\DataObjectGenerator
     */
    private $generatorService;
    /**
     * @param DataObjectGenerator $generatorService
     */
    public function __construct(DataObjectGenerator $generatorService)
    {

        $this->generatorService = $generatorService;

    }
    /**
     * @return \Heystack\Subsystem\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier(self::IDENTIFIER);
    }
    /**
     * @param \SS_HTTPRequest $request
     * @return mixed|void
     */
    public function process(\SS_HTTPRequest $request)
    {
        $this->generatorService->process($request->getVar('force'));
    }
    /**
     * @return DataObjectGenerator
     */
    public function getGeneratorService()
    {
        return $this->generatorService;
    }
}
