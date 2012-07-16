<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Subsystem\Core\Storage\Generator;

use Heystack\Subsystem\Core\Storage\Generator\GeneratorInterface;

use Heystack\Subsystem\Core\Processor\HandlerTrait;

/**
 * Hold and handles storage generators.
 *
 * This class provides storage and processing  of input processors,
 * processors are stored and accessed by an identifier
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 */
class Handler
{
    /**
     * Use the handler trait
     */
    use HandlerTrait;

    /**
     * Adds an input processor to the array of processors, storing it by its identifier
     * @param GeneratorInterface $processor The storage generator
     */
    public function addProcessor(GeneratorInterface $processor)
    {

        $this->processors[$processor->getIdentifier()] = $processor;

    }

    /**
    * Process all generators
    */
    public function process()
    {

        foreach ($this->processors as $processor) {
            $processor->process();
        }

    }

}
