<?php

namespace Heystack\Subsystem\Core;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Heystack\Subsystem\Core\Console\Command\GenerateContainer;

/**
 * Provides the functionality for regenerating the container after saving/deleting a dataobject
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Ecommerce-Deals
 */
trait GenerateContainerDataObjectTrait
{
    /**
     * Regenerate the container
     */
    public function onAfterWrite()
    {
        (new GenerateContainer())->run(
            new ArrayInput(array()),
            new NullOutput()
        );
    }
    /**
     * Regenerate the container
     */
    public function onAfterDelete()
    {
        $this->onAfterWrite();
    }

}