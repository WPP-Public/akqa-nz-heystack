<?php

namespace Heystack\Core;

/**
 * Contains the events that heystack itself fires
 * @package Heystack\Core
 */
final class Events
{
    /**
     * Fires before the request starts
     */
    const PRE_REQUEST = 'heystack.pre_request';
    /**
     * Fires after the request ends
     */
    const POST_REQUEST = 'heystack.post_request';
}
