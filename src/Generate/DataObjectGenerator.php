<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Generate namespace
 */
namespace Heystack\Core\Generate;

use Heystack\Core\Exception\ConfigurationException;
use Heystack\Core\State\State;

/**
 * Generates SilverStripe DataObject classes based of schemas
 *
 * Generates SilverStripe DataObject classes and extensions based on added schemas
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @author  Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
class DataObjectGenerator
{

    /**
     * @var array
     */
    private $schemas = [];
    /**
     * @var array
     */
    private $referenceSchemas = [];
    /**
     * @var array
     */
    private $processingFlatStorage = [];
    /**
     * @var \Heystack\Core\State\State
     */
    private $stateService;

    /**
     * @param State $stateService
     */
    public function __construct(State $stateService)
    {
        $this->stateService = $stateService;
    }

    /**
     * @param DataObjectGeneratorSchemaInterface $schema
     * @param bool                               $reference
     * @param bool                               $force
     */
    public function addSchema(
        DataObjectGeneratorSchemaInterface $schema,
        $reference = false,
        $force = false
    )
    {

        $identifier = strtolower($schema->getIdentifier()->getFull());

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

    /**
     * @param $identifier
     * @return bool
     */
    public function hasSchema($identifier)
    {
        return isset($this->schemas[$identifier]) || isset($this->referenceSchemas[$identifier]);

    }

    /**
     * @param $identifier
     * @return bool
     */
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

    /**
     * @param bool $force
     */
    public function process($force = false)
    {
        $dirMysite = BASE_PATH . '/mysite/code/HeystackStorage';
        $dirCache = $dirMysite . '/cache';

        // check if the generated directoru exists, if not create it
        if (!is_dir($dirMysite)) {

            $this->output('Creating: ' . $dirMysite);

            mkdir($dirMysite);

        }

        if (!is_dir($dirCache)) {

            $this->output('Creating: ' . $dirCache);

            mkdir($dirCache);

        }

        // get all the previously created objects and delete them
        $cachedFiles = glob($dirCache . '/Cached*', GLOB_NOSORT);

        foreach ($cachedFiles as $cachedFile) {

            $this->output('Deleting: ' . $cachedFile);

            unlink($cachedFile);

        }

        $managed_models = [];

        foreach ($this->schemas as $schema) {

            $identifier = $schema->getIdentifier()->getFull();

            $dataProviderID = $schema->getDataProviderIdentifier();

            $this->output('Processing schema: ' . $identifier);

            $flatStorage = $this->processFlatStorage(
                $schema->getFlatStorage(),
                $dataProviderID
            );

            $parentStorage = $this->processParentStorage(
                $schema->getParentStorage()
            );

            $childStorage = $this->processChildStorage(
                $schema->getChildStorage()
            );

            // names for the created objects
            $cachedObjectName = 'Cached' . $identifier;
            $storedObjectName = 'Stored' . $identifier;

            $fields = array_keys(is_array($flatStorage) ? $flatStorage : [])
                + array_keys(is_array($parentStorage) ? $parentStorage : []);

            // create the cached object
            $this->writeDataObject(
                $dirCache,
                $cachedObjectName,
                [
                    'db' => $flatStorage,
                    'has_one' => $parentStorage,
                    'has_many' => (array) $childStorage,
                    'summary_fields' => array_merge(['Created'], $fields),
                    'searchable_fields' => $fields,
                    'singular_name' => $identifier,
                    'plural_name' => $identifier . 's'
                ]
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

            $this->output('Finished ' . $identifier);

        }

        if ($managed_models && is_array($managed_models) && count($managed_models) > 0) {

            $this->writeModelAdmin(
                $dirCache,
                'GeneratedModelAdmin',
                [
                    'managed_models' => $managed_models,
                    'url_segment' => 'generated-admin',
                    'menu_title' => 'Admin'
                ]
            );

        }

        $this->output('Finished!');

    }

    /**
     * @param        $dir
     * @param        $name
     * @param bool   $statics
     * @param string $extends
     */
    protected function writeDataObject($dir, $name, $statics = false, $extends = 'DataObject')
    {

        $this->output('Writing DataObject: ' . $name . '...', '');

        if ($statics && is_array($statics)) {

            foreach ($statics as $key => $static) {

                $statics[$key] = $this->beautify(var_export($static, true));

            }

        } else {

            $statics = [];

        }

        file_put_contents(
            $dir . DIRECTORY_SEPARATOR . $name . '.php',
            singleton('ViewableData')->renderWith(
                HEYSTACK_BASE_PATH . '/code/Heystack/Subsystem/Core/Generate/templates/DataObject_php.ss',
                array_merge(
                    [
                        'PHPTag' => '<?php',
                        'Name' => $name,
                        'Extends' => $extends,
                        'db' => false,
                        'has_one' => false,
                        'has_many' => false,
                        'summary_fields' => false,
                        'searchable_fields' => false,
                        'singular_name' => false,
                        'plural_name' => false
                    ],
                    $statics
                )
            )
        );

        $this->output('Done!');

    }

    /**
     * @param        $dir
     * @param        $name
     * @param bool   $statics
     * @param string $extends
     */
    protected function writeModelAdmin($dir, $name, $statics = false, $extends = 'ModelAdmin')
    {

        $this->output('Writing ModelAdmin: ' . $name . '...', '');

        if ($statics && is_array($statics)) {

            foreach ($statics as $key => $static) {

                $statics[$key] = $this->beautify(var_export($static, true));

            }

        } else {

            $statics = [];

        }

        file_put_contents(
            $dir . DIRECTORY_SEPARATOR . $name . '.php',
            singleton('ViewableData')->renderWith(
                HEYSTACK_BASE_PATH . '/code/Heystack/Subsystem/Core/Generate/templates/ModelAdmin_php.ss',
                [
                    'PHPTag' => '<?php',
                    'Name' => $name,
                    'Extends' => $extends
                ]
                +
                $statics
            )
        );

        $this->output('Done!');

    }

    /**
     * @param $value
     * @return bool|string
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
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

    /**
     * @param $flatStorage
     * @param $identifier
     * @return array
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
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

                            $flatStorage[substr($value, 1) . $extraName] = $extraValue; //TODO: Fix me

                        }

                    }

                }

            }

        }

        unset($this->processingFlatStorage[$identifier]);

        return $flatStorage;

    }

    /**
     * @param $parentStorage
     * @return array
     */
    protected function processParentStorage($parentStorage)
    {

        if (is_array($parentStorage)) {

            foreach ($parentStorage as $name => $value) {

                if ($parentIdentifier = $this->isReference($value)) {

                    unset($parentStorage[$name]);
                    $parentStorage[$name] = 'Stored' . $this->getSchema($parentIdentifier)->getIdentifier()->getFull();

                }

            }

        }

        return $parentStorage;

    }

    /**
     * @param $childStorage
     * @return array
     */
    protected function processChildStorage($childStorage)
    {

        if (is_array($childStorage)) {

            foreach ($childStorage as $name => $value) {

                if ($childIdentifier = $this->isReference($value)) {

                    $childStorage[$name] = 'Stored' . $this->getSchema($childIdentifier)->getIdentifier()->getFull();

                }

            }

        }

        return $childStorage;

    }

    /**
     * @param         $content
     * @param  string $tab
     * @return mixed
     */
    protected function beautify($content, $tab = '    ')
    {
        return str_replace("\n", "\n" . $tab, $content);

    }

    /**
     * @param        $message
     * @param string $break
     */
    protected function output($message, $break = PHP_EOL)
    {

        echo $message, $break;

    }
}
