<?php

namespace Heystack\Subsystem\Core\Generate;

class DataObjectGenerator
{
    
    private $generators = array();
    
    public function addGenerator(GODSchemaInterface $generator)
    {
        
        $this->generators[] = $generator;
        
    }
    
    public function addYamlGenerator($file)
    {
        
        $this->addGenerator(new YamlGODSchema($file));
        
    }
    
    public function addDataObjectGenerator($className)
    {
        
        $this->addGenerator(new DataObjectSchema($className));
        
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
        
        foreach ($this->generators as $generator) {
            
            $flatStorage = $generator->getFlatStorage();
            $relatedStorage = $generator->getRelatedStorage();
            $identifier = $generator->getIdentifier();


            // names for the created objects
            $cachedObjectName = 'Cached' . $identifier;
            $storedObjectName = 'Stored' . $identifier;
            $cachedRelatedObjectName = 'Cached' . $identifier . 'RelatedData';
            $storedRelatedObjectName = 'Stored' . $identifier . 'RelatedData';

            // create the cached object
            file_put_contents($dir_cache . DIRECTORY_SEPARATOR . $cachedObjectName . '.php', singleton('ViewableData')->renderWith('CachedDataObject_php', array(
                'DataObjectName' => $cachedObjectName,
                'db' => var_export($flatStorage, true),
                'D' => '$',
                'P' => '<?php',
                'has_one' => false,
                'has_many' => var_export(array($storedRelatedObjectName => $storedRelatedObjectName), true)
            )));

            // create the storage object
            if (!file_exists($dir_mysite . DIRECTORY_SEPARATOR . $storedObjectName . '.php')) {

                file_put_contents($dir_mysite . DIRECTORY_SEPARATOR . $storedObjectName . '.php', singleton('ViewableData')->renderWith('StoredDataObject_php', array(
                    'DataObjectName' => $storedObjectName,
                    'D' => '$',
                    'P' => '<?php',
                    'ExtendedDataObject' => $cachedObjectName,
                    'has_one' => false,
                    'has_many' => false,
                    'summary_fields' => var_export(array_keys($flatStorage), true)
                )));

            }

            if (count($relatedStorage) > 0) {

                // create the cached object
                file_put_contents($dir_cache . DIRECTORY_SEPARATOR . $cachedRelatedObjectName . '.php', singleton('ViewableData')->renderWith('CachedDataObject_php', array(
                            'DataObjectName' => $cachedRelatedObjectName,
                            'db' => var_export($relatedStorage, true),
                            'D' => '$',
                            'P' => '<?php',
                            'has_one' => var_export(array($cachedObjectName => $cachedObjectName), true),
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
                        'has_many' => false,
                        'summary_fields' => var_export(array_keys($relatedStorage), true)
                    )));

                }
            }
            
        }
        
    }
    
}