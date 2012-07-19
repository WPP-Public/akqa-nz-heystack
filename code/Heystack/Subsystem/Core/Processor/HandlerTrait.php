<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Processor namespace
 */
namespace Heystack\Subsystem\Core\Processor;

/**
 * Provides standard processor handling functionality.
 *
 * Stores an array of processors and provides methods for setting, getting
 * and adding processors to the array. Forces traiting class to implement abstract methods.
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
trait HandlerTrait
{
    /**
     * Stores processors by an identifier key
     * @var array
     */
    private $processors = array();

    /**
     * Force traiting class to implement a method to add processors
     */
    abstract public function addProcessor();

    /**
     *  Force traiting class to implement a method for processing
     */
    abstract public function process();

    /**
     * Return a processor by an identifier if it exists
     * @param  string $name The processor identifier
     * @return mixed  The processor if it exists
     */
    public function getProcessor($name)
    {

        return isset($this->processors[$name]) ? $this->processors[$name] : false;

    }

    /**
     * Check if a processor is on the array
     * @param  string  $name The processor identifier
     * @return boolean Whether or not the processor exists
     */
    public function hasProcessor($name)
    {

        return isset($this->processors[$name]);

    }

    /**
     * Return all processors
     * @return array The processors or an empty array
     */
    public function getProcessors()
    {

        return $this->processors;

    }

    /**
     * Set processors explicitly. This method uses the addProcessor method which
     * should enforce what type of objects can be added to the array
     * @uses self::addProcessor
     * @param array $processors An array of processors
     */
    public function setProcessors(array $processors)
    {

        $this->processors = array();

        foreach ($processors as $processor) {

            $this->addProcessor($processor);

        }

    }

}
