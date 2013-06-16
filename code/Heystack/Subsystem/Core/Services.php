<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Core namespace
 */
namespace Heystack\Subsystem\Core;

/**
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 *
 */
final class Services
{
    const CONSOLE_APPLICATION = 'console.application';
    const MEMCACHE = 'memcache';
    const SESSION = 'session';
    const BACKEND_MEMCACHE = 'backend_memcache';
    const BACKEND_GLOBAL_MEMCACHE = 'backend_global_memcache';
    const BACKEND_SESSION = 'backend_session';
    const STATE = 'state';
    const STATE_GLOBAL = 'state_global';
    const CONFIG = 'config';
    const EVENT_DISPATCHER = 'event_dispatcher';
    const INPUT_PROCESSOR_HANDLER = 'input_processor_handler';
    const OUTPUT_PROCESSOR_HANDLER = 'output_processor_handler';

    const MONOLOG_MULTILINE_FORMATTER = 'monolog_multiline_formatter';
    const MONOLOG_WEB_PROCESSOR = 'monolog_web_processor';
    const MONOLOG_PEAK_MEMORY_PROCESSOR = 'monolog_peak_memory_processor';
    const MONOLOG_INTROSPECTION_PROCESSOR = 'monolog_introspection_processor';
    const MONOLOG_STREAM_HANDLER = 'monolog_stream_handler';
    const MONOLOG_MAIL_HANDLER = 'monolog_mail_handler';
    const MONOLOG_CHROME_HANDLER = 'monolog_chrome_handler';
    const MONOLOG = 'monolog';
    const MONOLOG_DEV = 'monolog_dev';

    const DATAOBJECT_GENERATOR = 'dataobject_generator';
    const DATAOBJECT_GENERATOR_INPUT_PROCESSOR = 'dataobject_generator_input_processor';

    const SS_ORM_BACKEND = 'ss_orm_backend';
    const STORAGE = 'storage';
}
