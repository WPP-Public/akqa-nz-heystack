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

    public function process()
    {

        // get all the previously created objects and delete them
        $cachedFiles = glob(BASE_PATH . '/heystack/cache/Stored*', GLOB_NOSORT);

        foreach ($cachedFiles as $cachedFile) {

            unlink($cachedFile);

        }

        $dir_mysite = BASE_PATH . DIRECTORY_SEPARATOR . 'mysite/code/HeystackStorage';
        $dir_cache = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache');

        // check if the generated directoru exists, if not create it
        if (!is_dir($dir_mysite)) {
            mkdir($dir_mysite );
        }

        // check if the cached directoru exists, if not create it
        if (!is_dir($dir_cache)) {
            mkdir($dir_cache );
        }

        foreach ($this->schemas as $schema) {

            $flatStorage = $schema->getFlatStorage();
            $relatedStorage = $schema->getRelatedStorage();
            $parentStorage = $schema->getParentStorage();
            $childStorage = $schema->getChildStorage();
            $identifier = $schema->getIdentifier();

            // names for the created objects
            $cachedObjectName = 'Cached' . $identifier;
            $storedObjectName = 'Stored' . $identifier;
            $cachedRelatedObjectName = 'Cached' . $identifier . 'RelatedData';
            $storedRelatedObjectName = 'Stored' . $identifier . 'RelatedData';
            
            $has_many = array_merge($childStorage, array($storedRelatedObjectName => $storedRelatedObjectName));
            
            // create the cached object
            file_put_contents($dir_cache . DIRECTORY_SEPARATOR . $cachedObjectName . '.php', singleton('ViewableData')->renderWith('CachedDataObject_php', array(
                'DataObjectName' => $cachedObjectName,
                'db' => var_export($flatStorage, true),
                'D' => '$',
                'P' => '<?php',
                'has_one' => $parentStorage,
                'has_many' => var_export($has_many, true)
            )));

            // create the storage object
            if (!file_exists($dir_mysite . DIRECTORY_SEPARATOR . $storedObjectName . '.php')) {

                file_put_contents($dir_mysite . DIRECTORY_SEPARATOR . $storedObjectName . '.php', singleton('ViewableData')->renderWith('StoredDataObject_php', array(
                    'DataObjectName' => $storedObjectName,
                    'D' => '$',
                    'P' => '<?php',
                    'ExtendedDataObject' => $cachedObjectName,
                    'has_one' => false,
                    'has_many' => false
                )));

            }

            if (count($relatedStorage) > 0) {

                // create the cached object
                file_put_contents($dir_cache . DIRECTORY_SEPARATOR . $cachedRelatedObjectName . '.php', singleton('ViewableData')->renderWith('CachedDataObject_php', array(
                            'DataObjectName' => $cachedRelatedObjectName,
                            'db' => var_export($relatedStorage, true),
                            'D' => '$',
                            'P' => '<?php',
                            'has_one' => var_export(array($storedObjectName => $storedObjectName), true),
                            'has_many' => false
                )));

                // create the storage object
                if (!file_exists($dir_mysite . DIRECTORY_SEPARATOR . $storedRelatedObjectName . '.php')) {

                    file_put_contents($dir_mysite . DIRECTORY_SEPARATOR . $storedRelatedObjectName . '.php', singleton('ViewableData')->renderWith('StoredDataObject_php', array(
                        'DataObjectName' => $storedRelatedObjectName,
                        'D' => '$',
                        'P' => '<?php',
                        'ExtendedDataObject' => $cachedRelatedObjectName,
                        'has_one' => false,
                        'has_many' => false
                    )));

                }
            }

        }

    }

}
