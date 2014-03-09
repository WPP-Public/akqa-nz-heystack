<?php

define('BASE_PATH', dirname(dirname(__DIR__)));

chdir(dirname(dirname(__FILE__)));

/**
 * Start: Load silverstripe
 */
$_FILE_TO_URL_MAPPING[BASE_PATH] = 'http://localhost';

require_once BASE_PATH . '/framework/core/Core.php';

if (function_exists('mb_http_output')) {
    mb_http_output('UTF-8');
    mb_internal_encoding('UTF-8');
}
/**
 * End: Load silverstripe
 */
