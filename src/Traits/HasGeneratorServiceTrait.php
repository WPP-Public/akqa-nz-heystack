<?php

namespace Heystack\Core\Traits;

use Heystack\Core\DataObjectGenerate\DataObjectGenerator;

/**
 * Allows a using class to set a generator service
 * @package Heystack\Core\Traits
 */
trait HasGeneratorServiceTrait
{
    /**
     * @var \Heystack\Core\DataObjectGenerate\DataObjectGenerator
     */
    protected $generatorService;

    /**
     * @param \Heystack\Core\DataObjectGenerate\DataObjectGenerator $generatorService
     */
    public function setGeneratorService(DataObjectGenerator $generatorService)
    {
        $this->generatorService = $generatorService;
    }

    /**
     * @return \Heystack\Core\DataObjectGenerate\DataObjectGenerator
     */
    public function getGeneratorService()
    {
        return $this->generatorService;
    }
} 