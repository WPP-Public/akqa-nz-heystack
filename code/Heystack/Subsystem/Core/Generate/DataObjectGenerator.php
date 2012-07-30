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
    private $processingFlatStorage = array();

    public function addSchema(DataObjectGeneratorSchemaInterface $schema)
    {
        
        $identifier = $schema->getIdentifier();
        
        if ($this->hasSchema($identifier)) {
        
            $this->schemas[$identifier]->mergeSchema($schema);
            
        } else {
        
            $this->schemas[$identifier] = $schema;
         
        }

    }

    public function hasSchema($identifier)
    {

        return isset($this->schemas[$identifier]) ?  $this->schemas[$identifier] : false;

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

        $managed_models = array();

        foreach ($this->schemas as $schema) {

            if (!$schema->getReferenceOnly()) {

                $identifier = $schema->getIdentifier();

                $this->output('Processing schema: ' . $identifier);

                $flatStorage    = $this->processFlatStorage($schema->getFlatStorage(), $identifier);
                $relatedStorage = $this->processRelatedStorage($schema->getRelatedStorage(), $identifier);
                $parentStorage  = $this->processParentStorage($schema->getParentStorage(), $identifier);
                $childStorage   = $this->processChildStorage($schema->getChildStorage(), $identifier);

                // names for the created objects
                $cachedObjectName           = 'Cached' . $identifier;
                $storedObjectName           = 'Stored' . $identifier;
                $cachedRelatedObjectName    = 'Cached' . $identifier . 'RelatedData';
                $storedRelatedObjectName    = 'Stored' . $identifier . 'RelatedData';

                // create the cached object
                $this->writeDataObject(
                    $dirCache,
                    $cachedObjectName,
                    array(
                        'db' => $flatStorage,
                        'has_one' => $parentStorage,
                        'has_many' => $childStorage + array(
                            $storedRelatedObjectName => $storedRelatedObjectName
                        ),
                        'summary_fields' => array_keys(is_array($flatStorage) ? $flatStorage : array()) + array_keys(is_array($parentStorage) ? $parentStorage : array())
                    )
                );

                $managed_models[] = $storedObjectName;

                // create the storage object
                if ($force || !file_exists($dirMysite . DIRECTORY_SEPARATOR . $storedObjectName . '.php')) {

                    $this->writeDataObject(
                        $dirMysite,
                        $storedObjectName,
                        false,
                        $cachedObjectName
                    );

                }

                if ($relatedStorage && is_array($relatedStorage) && count($relatedStorage) > 0) {

                    $this->writeDataObject(
                        $dirCache,
                        $cachedRelatedObjectName,
                        array(
                            'db' => $relatedStorage,
                            'has_one' => array(
                                $storedObjectName => $storedObjectName
                            )
                        )
                    );

                    $managed_models[] = $storedRelatedObjectName;

                    // create the storage object
                    if ($force || !file_exists($dirMysite . DIRECTORY_SEPARATOR . $storedRelatedObjectName . '.php')) {

                        $this->writeDataObject(
                            $dirMysite,
                            $storedRelatedObjectName,
                            false,
                            $cachedRelatedObjectName
                        );

                    }

                }

                $this->output('Finished ' . $identifier);

            }

        }

        if ($managed_models && is_array($managed_models) && count($managed_models) > 0) {

            $this->writeModelAdmin(
                $dirCache,
                'GeneratedModelAdmin',
                array(
                    'managed_models' => $managed_models,
                    'url_segment' => 'generated-admin',
                    'menu_title' => 'Admin'
                )
            );

        }

        $this->output('Finished!');

        exit;

    }

    protected function writeDataObject($dir, $name, $statics = false, $extends = 'DataObject')
    {

        $this->output('Writing DataObject: ' . $name . '...', '');

        if ($statics && is_array($statics)) {

            foreach ($statics as $key => $static) {

                $statics[$key] = $this->beautify(var_export($static, true));

            }

        } else {

            $statics = array();

        }

        file_put_contents(
            $dir . DIRECTORY_SEPARATOR . $name . '.php',
            singleton('ViewableData')->renderWith(
                'DataObject_php',
                array_merge(array(
                    'D'                 => '$',
                    'P'                 => '<?php',
                    'Name'              => $name,
                    'Extends'           => $extends,
                    'db'                => false,
                    'has_one'           => false,
                    'has_many'          => false,
                    'summary_fields'    => false,
                ), $statics)
            )
        );

        $this->output('Done!');

    }

    protected function writeModelAdmin($dir, $name, $statics = false, $extends = 'ModelAdmin')
    {

        $this->output('Writing ModelAdmin: ' . $name . '...', '');

        if ($statics && is_array($statics)) {

            foreach ($statics as $key => $static) {

                $statics[$key] = $this->beautify(var_export($static, true));

            }

        } else {

            $statics = array();

        }

        file_put_contents(
            $dir . DIRECTORY_SEPARATOR . $name . '.php',
            singleton('ViewableData')->renderWith(
                'ModelAdmin_php',
                array(
                    'D'         => '$',
                    'P'         => '<?php',
                    'Name'      => $name,
                    'Extends'   => $extends
                )
                +
                $statics
            )
        );

        $this->output('Done!');

    }

    protected function processFlatStorage($flatStorage, $identifier)
    {

        $this->processingFlatStorage[$identifier] = $identifier;

        if (is_array($flatStorage)) {

            foreach ($flatStorage as $name => $value) {

                $value = trim($value);

                if ($value[0] == '+') {

                    unset($flatStorage[$name]);

                    $flatIdentifier = substr($value, 1);

                    if ($this->hasSchema($flatIdentifier)) {

                        if (isset($this->processingFlatStorage[$flatIdentifier])) {

                            throw new \Exception('Circular reference in flat storage');

                        }

                        $extraFlatStorage = $this->processFlatStorage($this->schemas[$flatIdentifier]->getFlatStorage(), $flatIdentifier);

                        if (is_array($extraFlatStorage)) {

                            foreach ($extraFlatStorage as $extraName => $extraValue) {

                                $flatStorage[$flatIdentifier . $extraName] = $extraValue;

                            }

                        }

                    }

                }

            }

        }

        unset($this->processingFlatStorage[$identifier]);

        return $flatStorage;

    }

    protected function processParentStorage($parentStorage, $identifier)
    {

        if (is_array($parentStorage)) {

            foreach ($parentStorage as $name => $value) {

                $value = trim($value);

                if ($value[0] == '+') {

                    unset($parentStorage[$name]);

                    $parentIdentifier = substr($value, 1);

                    if ($this->hasSchema($parentIdentifier)) {

                        $parentStorage[$name] = 'Stored' . $parentIdentifier;

                    }

                }

            }

        }

        return $parentStorage;

    }

    protected function processRelatedStorage($relatedStorage, $identifier)
    {

        if (is_array($relatedStorage)) {

            foreach ($relatedStorage as $name => $value) {

                $value = trim($value);

                if ($value[0] == '+') {

                    unset($relatedStorage[$name]);

                    $relatedIdentifier = substr($value, 1);

                    if ($this->hasSchema($relatedIdentifier)) {

                        if (isset($this->processingRelatedStorage[$relatedIdentifier])) {

                            throw new \Exception('Circular reference in related storage');

                        }

                        $extraRelatedStorage = $this->schemas[$relatedIdentifier]->getFlatStorage();

                        if (is_array($extraRelatedStorage)) {

                            foreach ($extraRelatedStorage as $extraName => $extraValue) {

                                $relatedStorage[$relatedIdentifier . $extraName] = $extraValue;

                            }

                        }

                    }

                }

            }

        }

        return $relatedStorage;

    }

    protected function processChildStorage($childStorage, $identifier)
    {

        if (is_array($childStorage)) {

            foreach ($childStorage as $name => $value) {

                $value = trim($value);

                if ($value[0] == '+') {

                    $childIdentifier = substr($value, 1);

                    if ($this->hasSchema($childIdentifier)) {

                        $childStorage[$name] = 'Stored' . $childIdentifier;

                    } else {

                        unset($childStorage[$name]);

                    }

                }

            }

        }

        return $childStorage;

    }

    protected function beautify($content, $tab = '    ')
    {

        return str_replace("\n", "\n" . $tab, $content);

    }

    protected function output($message, $break = PHP_EOL)
    {

        echo $message, $break;

    }

}
