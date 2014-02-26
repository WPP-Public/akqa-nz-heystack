<?php

namespace Heystack\Core;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

/**
 * Class EventDispatcher
 * @package Heystack\Core
 */
class EventDispatcher extends SymfonyEventDispatcher
{
    /**
     * @var
     */
    protected $enabled = true;

    /**
     * @param  string     $eventName
     * @param  Event      $event
     * @return bool|Event
     */
    public function dispatch($eventName, Event $event = null)
    {
        if ($this->enabled) {
            return parent::dispatch($eventName, $event);
        } else {
            return $event ? : new Event();
        }
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
