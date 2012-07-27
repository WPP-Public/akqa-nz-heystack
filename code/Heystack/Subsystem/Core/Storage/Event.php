<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Subsystem\Core\Storage;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

/**
 *
 *
 *
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class Event extends SymfonyEvent
{

    private $parentReference = null;

    public function __construct($parentReference)
    {

        $this->parentReference = $parentReference;

    }

    public function getParentReference()
    {

        return $this->parentReference;

    }

}
