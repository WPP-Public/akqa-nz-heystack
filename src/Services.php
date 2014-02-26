<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Core namespace
 */
namespace Heystack\Core;

/**
 *
 * @copyright  Heyday
 * @author     Cam Spiers <cameron@heyday.co.nz>
 * @package    Heystack
 */
final class Services
{
    const BACKEND_MEMCACHE = 'backend_memcache';
    const BACKEND_GLOBAL_MEMCACHE = 'backend_global_memcache';
    const BACKEND_SESSION = 'backend_session';
    const CONSOLE_APPLICATION = 'console.application';
    const DATAOBJECT_GENERATOR = 'dataobject_generator';
    const DATAOBJECT_GENERATOR_INPUT_PROCESSOR = 'dataobject_generator_input_processor';
    const EVENT_DISPATCHER = 'event_dispatcher';
    const INPUT_PROCESSOR_HANDLER = 'input_processor_handler';
    const LOGGER = 'logger';
    const MEMCACHE = 'memcache';
    const OUTPUT_PROCESSOR_HANDLER = 'output_processor_handler';
    const SESSION = 'session';
    const SS_ORM_BACKEND = 'ss_orm_backend';
    const STATE = 'state';
    const STATE_GLOBAL = 'state_global';
    const STORAGE = 'storage';
}
