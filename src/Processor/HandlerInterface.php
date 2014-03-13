<?php

namespace Heystack\Core\Processor;

/**
 * The interface that handlers both Output and Input need to implement
 * 
 * Used in combination with HandlerTrait
 * 
 * @package Heystack\Core\Processor
 */
interface HandlerInterface
{
    /**
     * Return a processor by an identifier if it exists
     * @param  string $name The processor identifier
     * @return mixed  The processor if it exists
     */
    public function getProcessor($name);

    /**
     * Check if a processor is on the array
     * @param  string  $name The processor identifier
     * @return boolean Whether or not the processor exists
     */
    public function hasProcessor($name);

    /**
     * Return all processors
     * @return array The processors or an empty array
     */
    public function getProcessors();

    /**
     * Set processors explicitly. This method uses the addProcessor method which
     * should enforce what type of objects can be added to the array
     * @uses self::addProcessor
     * @param array $processors An array of processors
     */
    public function setProcessors(array $processors);
}
