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

use Heystack\Subsystem\Core\State\State;

use Heystack\Subsystem\Core\Exception\ConfigurationException;

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
    private $referenceSchemas = array();
    private $processingFlatStorage = array();
    private $stateService;

    public function __construct(State $stateService)
    {
        $this->stateService = $stateService;
    }

    public function addSchema(
        DataObjectGeneratorSchemaInterface $schema,
        $reference = false,
        $force = false
    )
    {

        $identifier = strtolower($schema->getIdentifier());

        if ($reference) {

            $this->referenceSchemas[$identifier] = $schema;

        } else {

            if ($this->hasSchema($identifier) && !$force) {

                $this->schemas[$identifier]->mergeSchema($schema);

            } else {

                $this->schemas[$identifier] = $schema;

            }

        }

    }

    public function addYamlSchema($file, $reference = false, $force = false, $realpath = false)
    {

        $this->addSchema(
            new YamlDataObjectSchema(
                $realpath ? $file : dirname(HEYSTACK_BASE_PATH) . '/' . $file,
                $this->stateService
            ),
            $reference,
            $force
        );

    }

    public function addJsonSchema($file, $reference = false, $force = false, $realpath = false)
    {

        $this->addSchema(
            new JsonDataObjectSchema(
                $realpath ? $file : dirname(HEYSTACK_BASE_PATH) . '/' . $file,
                $this->stateService
            ),
            $reference,
            $force
        );

    }

    public function hasSchema($identifier)
    {

        return isset($this->schemas[$identifier]) || isset($this->referenceSchemas[$identifier]);

    }

    public function getSchema($identifier)
    {

        if ($this->hasSchema($identifier)) {

            return isset($this->schemas[$identifier])
                ? $this->schemas[$identifier]
                : $this->referenceSchemas[$identifier];

        } else {

            return false;

        }

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

            $identifier = $schema->getIdentifier();

            $dataProviderID = $schema->getDataProviderIdentifier();

            $this->output('Processing schema: ' . $identifier);

            $flatStorage = $this->processFlatStorage(
                $schema->getFlatStorage(),
                $dataProviderID
            );

            $relatedStorage = $this->processRelatedStorage(
                $schema->getRelatedStorage()
            );

            $hasRelatedStorage = $relatedStorage && is_array($relatedStorage) && count($relatedStorage) > 0;

            $parentStorage = $this->processParentStorage(
                $schema->getParentStorage()
            );

            $childStorage = $this->processChildStorage(
                $schema->getChildStorage()
            );

            // names for the created objects
            $cachedObjectName           = 'Cached' . $identifier;
            $storedObjectName           = 'Stored' . $identifier;
            $cachedRelatedObjectName    = 'Cached' . $identifier . 'RelatedData';
            $storedRelatedObjectName    = 'Stored' . $identifier . 'RelatedData';

            $fields = array_keys(is_array($flatStorage) ? $flatStorage : array())
                + array_keys(is_array($parentStorage) ? $parentStorage : array());

            // create the cached object
            $this->writeDataObject(
                $dirCache,
                $cachedObjectName,
                array(
                    'db'                => $flatStorage,
                    'has_one'           => $parentStorage,
                    'has_many'          => $childStorage + ($hasRelatedStorage ? array(
                        $storedRelatedObjectName => $storedRelatedObjectName
                    ): array()),
                    'summary_fields'    => array_merge(array('Created'), $fields),
                    'searchable_fields' => $fields,
                    'singular_name'     => $identifier,
                    'plural_name'       => $identifier . 's'
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

            if ($hasRelatedStorage) {

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
                if (
                    $force ||
                    !file_exists($dirMysite . DIRECTORY_SEPARATOR . $storedRelatedObjectName . '.php')
                ) {

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
                    'searchable_fields' => false,
                    'singular_name'     => false,
                    'plural_name'       => false
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

    public function isReference($value)
    {

        $value = trim($value);

        if ($value[0] == '+') {

            $identifier = strtolower(substr($value, 1));

            if ($this->hasSchema($identifier)) {

                return $identifier;

            }

            throw new ConfigurationException("Reference to undefined schema: $identifier");

        }

        return false;

    }

    protected function processFlatStorage($flatStorage, $identifier)
    {

        $this->processingFlatStorage[$identifier] = $identifier;

        if (is_array($flatStorage)) {

            foreach ($flatStorage as $name => $value) {

                if ($flatIdentifier = $this->isReference($value)) {

                    unset($flatStorage[$name]);

                    if (isset($this->processingFlatStorage[$flatIdentifier])) {

                        throw new ConfigurationException('Circular reference in flat storage');

                    }

                    $extraFlatStorage = $this->processFlatStorage(
                        $this->getSchema($flatIdentifier)->getFlatStorage(),
                        $flatIdentifier
                    );

                    if (is_array($extraFlatStorage)) {

                        foreach ($extraFlatStorage as $extraName => $extraValue) {

                            $flatStorage[substr($value, 1) . $extraName] = $extraValue;

                        }

                    }

                }

            }

        }

        unset($this->processingFlatStorage[$identifier]);

        return $flatStorage;

    }

    protected function processParentStorage($parentStorage)
    {

        if (is_array($parentStorage)) {

            foreach ($parentStorage as $name => $value) {

                if ($parentIdentifier = $this->isReference($value)) {

                    unset($parentStorage[$name]);
                    $parentStorage[$name] = 'Stored' . $parentIdentifier;

                }

            }

        }

        return $parentStorage;

    }

    protected function processRelatedStorage($relatedStorage)
    {

        if (is_array($relatedStorage)) {

            foreach ($relatedStorage as $name => $value) {

                if ($relatedIdentifier = $this->isReference($value)) {

                    unset($relatedStorage[$name]);

                    if (isset($this->processingRelatedStorage[$relatedIdentifier])) {

                        throw new ConfigurationException('Circular reference in related storage');

                    }

                    $extraRelatedStorage = $this->getSchema($relatedIdentifier)->getFlatStorage();

                    if (is_array($extraRelatedStorage)) {

                        foreach ($extraRelatedStorage as $extraName => $extraValue) {

                            $relatedStorage[substr($value, 1) . $extraName] = $extraValue;

                        }

                    }

                }

            }

        }

        return $relatedStorage;

    }

    protected function processChildStorage($childStorage)
    {

        if (is_array($childStorage)) {

            foreach ($childStorage as $name => $value) {

                if ($childIdentifier = $this->isReference($value)) {

                    $childStorage[$name] = 'Stored' . substr($value, 1);

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
