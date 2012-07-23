<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Generate namespace
 */
namespace Heystack\Subsystem\Core\Generate;

/**
 * Generates SilverStripe DataObject classes based of schemas
 *
 * Generates SilverStripe DataObject classes and extensions based on added schemas
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
class DataObjectGenerator
{

    private $schemas = array();

    public function addSchema(DataObjectGeneratorSchemaInterface $schema)
    {

        $this->schemas[] = $schema;

    }

    public function addYamlSchema($file)
    {

        $this->addSchema(new YamlDataObjectGeneratorSchema($file));

    }

    public function addDataObjectSchema($className)
    {

        $this->addSchema(new DataObjectSchema($className));

    }

    public function process($force = false)
    {

        // get all the previously created objects and delete them
        $cachedFiles = glob(BASE_PATH . '/heystack/cache/Stored*', GLOB_NOSORT);

        foreach ($cachedFiles as $cachedFile) {

            $this->output('Deleting: ' . $cachedFile);

            unlink($cachedFile);

        }

        $dirMysite = BASE_PATH . DIRECTORY_SEPARATOR . 'mysite/code/HeystackStorage';
        $dirCache = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache');

        // check if the generated directoru exists, if not create it
        if (!is_dir($dirMysite)) {


            $this->output('Creating: ' . $dirMysite);

            mkdir($dirMysite);

        }

        // check if the cached directoru exists, if not create it
        if (!is_dir($dirCache)) {

            $this->output('Creating: ' . $dirCache);

            mkdir($dirCache);

        }

        foreach ($this->schemas as $schema) {

            $identifier = $schema->getIdentifier();
            
            $this->output('Processing schema: ' . $identifier);

            $flatStorage = $schema->getFlatStorage();
            $relatedStorage = $schema->getRelatedStorage();
            $parentStorage = $schema->getParentStorage();
            $childStorage = $schema->getChildStorage();

            // names for the created objects
            $cachedObjectName = 'Cached' . $identifier;
            $storedObjectName = 'Stored' . $identifier;
            $cachedRelatedObjectName = 'Cached' . $identifier . 'RelatedData';
            $storedRelatedObjectName = 'Stored' . $identifier . 'RelatedData';
            
            // create the cached object
            $this->writeDataObject(
                $dirCache,
                $cachedObjectName,
                $flatStorage,
                $parentStorage,
                array_merge(
                    $childStorage,
                    array(
                        $storedRelatedObjectName => $storedRelatedObjectName
                    )
                )
            );

            // create the storage object
            if ($force || !file_exists($dirMysite . DIRECTORY_SEPARATOR . $storedObjectName . '.php')) {

                $this->writeDataObject(
                    $dirMysite,
                    $storedObjectName,
                    false,
                    false,
                    false,
                    $cachedObjectName
                );

            }

            if (count($relatedStorage) > 0) {

                $this->writeDataObject(
                    $dirCache,
                    $cachedRelatedObjectName,
                    $relatedStorage,
                    array(
                        $storedObjectName => $storedObjectName
                    )
                );

                // create the storage object
                if ($force || !file_exists($dirMysite . DIRECTORY_SEPARATOR . $storedRelatedObjectName . '.php')) {

                    $this->writeDataObject(
                        $dirMysite,
                        $storedRelatedObjectName,
                        false,
                        false,
                        false,
                        $cachedRelatedObjectName
                    );

                }
            }

            $this->output('Finished ' . $identifier);

        }

        $this->output('Finished!');

        exit;

    }

    protected function writeDataObject($dir, $name, $db = false, $has_one = false, $has_many = false, $extends = 'DataObject')
    {

        $this->output('Writing DataObject: ' . $name . '...', '');

        file_put_contents(
            $dir . DIRECTORY_SEPARATOR . $name . '.php',
            singleton('ViewableData')->renderWith(
                'CachedDataObject_php',
                array(
                    'D'         => '$',
                    'P'         => '<?php',
                    'Name'      => $name,
                    'Extends'   => $extends,
                    'db'        => is_array($db) ? var_export($db, true) : false,
                    'has_one'   => is_array($has_one) ? var_export($has_one, true) : false,
                    'has_many'  => is_array($has_many) ? var_export($has_many, true) : false
                )
            )
        );

        $this->output('Done!');

    }

    protected function output($message, $break = PHP_EOL)
    {

        echo $message, $break;

    }

}
