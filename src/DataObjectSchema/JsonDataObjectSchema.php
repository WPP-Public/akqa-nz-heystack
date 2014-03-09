<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Generate namespace
 */
namespace Heystack\Core\DataObjectSchema;

use Heystack\Core\Exception\ConfigurationException;

/**
 * Uses json files to provide a schema for dataobject class creation
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class JsonDataObjectSchema extends FileDataObjectSchema
{
    /**
     * List of json errors
     * @var array
     */
    protected static $errors = array(
        JSON_ERROR_NONE             => null,
        JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH   => 'Underflow or the modes mismatch',
        JSON_ERROR_CTRL_CHAR        => 'Unexpected control character found',
        JSON_ERROR_SYNTAX           => 'Syntax error, malformed JSON',
        JSON_ERROR_UTF8             => 'Malformed UTF-8 characters, possibly incorrectly encoded'
    );

    /**
     * @param string $file
     * @return array
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
    protected function parseFile($file)
    {
        if (!file_exists($file)) {
            throw new ConfigurationException(
                sprintf(
                    "File '%s' doesn't exist",
                    $file
                )
            );
        }

        $config = json_decode(file_get_contents($file), true);

        if ($config === null) {
            throw new ConfigurationException(
                sprintf(
                    "Json file '%s' is invalid: %s",
                    $file,
                    $this->getLastJsonError()
                )
            );
        }

        return $config;
    }

    /**
     * Get the last error
     * @return string
     */
    protected function getLastJsonError()
    {
        $error = json_last_error();
        return array_key_exists($error, self::$errors) ? self::$errors[$error] : "Unknown error ({$error})";
    }
}
