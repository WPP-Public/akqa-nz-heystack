<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Subsystem\Core\Storage\Backends\SilverStripeOrm;

use Heystack\Subsystem\Core\Storage\StorableInterface;
use Heystack\Subsystem\Core\Storage\BackendInterface;
use Heystack\Subsystem\Core\Storage\Event;
use Heystack\Subsystem\Core\Generate\DataObjectGenerator;
use Heystack\Subsystem\Core\Generate\DataObjectGeneratorSchemaInterface;

use Heystack\Subsystem\Core\Exception\ConfigurationException;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 *
 *
 *
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class Backend implements BackendInterface
{
    /**
     *
     */
    const IDENTIFIER = 'silverstripe_orm';
    /**
     * @var null|\Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $eventService = null;
    /**
     * @var \Heystack\Subsystem\Core\Generate\DataObjectGenerator|null
     */
    private $generatorService = null;
    /**
     * @var array
     */
    private $dataProviders = array();
    /**
     * @param EventDispatcher     $eventService
     * @param DataObjectGenerator $generatorService
     */
    public function __construct(
        EventDispatcher $eventService,
        DataObjectGenerator $generatorService
    ) {
        $this->eventService = $eventService;
        $this->generatorService = $generatorService;
    }
    /**
     * @param StorableInterface $dataProvider
     */
    public function addDataProvider(StorableInterface $dataProvider)
    {
        $this->dataProviders[$dataProvider->getStorableIdentifier()] = $dataProvider;
    }
    /**
     * @param $dataProviderIdentifier
     * @return bool
     */
    public function hasDataProvider($dataProviderIdentifier)
    {
        return isset($this->dataProviders[$dataProviderIdentifier]);
    }
    /**
     * @return string
     */
    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
    /**
     * @param DataObjectGeneratorSchemaInterface $schema
     * @param StorableInterface                  $dataProvider
     * @param StorableInterface                  $object
     * @return mixed
     * @throws \Heystack\Subsystem\Core\Exception\ConfigurationException
     */
    protected function writeStoredDataObject(
        DataObjectGeneratorSchemaInterface $schema,
        StorableInterface $dataProvider,
        StorableInterface $object
    ) {

        $saveable = 'Stored' . $schema->getIdentifier();

        $storedObject = new $saveable();

        $data = $dataProvider->getStorableData();
        $writeableData = $object->getStorableData();

        foreach ($schema->getFlatStorage() as $key => $value) {

            $reference = $this->generatorService->isReference($value);

            if ($reference) {

                $referenceSchema = $this->generatorService->getSchema($reference);

                if ($this->hasDataProvider($referenceSchema->getDataProviderIdentifier())) {

                    if ($referenceSchema instanceof DataObjectGeneratorSchemaInterface) {

                        $referenceData = $this->dataProviders[$referenceSchema->getDataProviderIdentifier(
                        )]->getStorableData();

                        foreach (array_keys($referenceSchema->getFlatStorage()) as $referenceKey) {

                            if (isset($referenceData['flat'][$referenceKey])) {

                                $storedObject->{$key . $referenceKey} = $referenceData['flat'][$referenceKey];

                            } else {

                                throw new ConfigurationException("No data found for key: $key on identifier: $reference");

                            }

                        }

                    } else {

                        throw new ConfigurationException("No schema found for identifier: $reference");

                    }

                } else {

                    throw new ConfigurationException('Reference in flat schema didn\'t have an associated data provider');

                }

            } else {

                if (array_key_exists($key, $writeableData['flat'])) {

                    $storedObject->$key = $writeableData['flat'][$key];

                } else {

                    throw new ConfigurationException("No data found for key: $key on identifier: " . $object->getStorableIdentifier(
                    ));

                }

            }

        }

        // @todo this should be in the config?
        if (isset($data['parent']) && isset($writeableData['flat']['ParentID'])) {
            $storedObject->ParentID = $writeableData['flat']['ParentID'];
        }

        $storedObject->write();

        return $storedObject;

    }

    /**
     * @param DataObjectGeneratorSchemaInterface $schema
     * @param                                    $storedObject
     * @throws \Heystack\Subsystem\Core\Exception\ConfigurationException
     */
    protected function writeStoredRelatedDataObject(DataObjectGeneratorSchemaInterface $schema, $storedObject)
    {
        $relatedStorage = $schema->getRelatedStorage();

        if ($relatedStorage) {

            throw new \RuntimeException('Related storage is buggy and not yet supported');

            $saveable = "Stored{$data['id']}RelatedData";

            foreach ($relatedStorage as $key => $value) {

                $storedManyObject = new $saveable();
                $storedManyObject->{"Stored{$data['id']}ID"} = $storedObject->ID;

                if ($reference = $this->generatorService->isReference($value)) {

                    if ($this->hasDataProvider($reference)) {

                        $referenceSchema = $this->generatorService->getSchema($reference);
                        $referenceData = $this->dataProviders[$reference]->getStorableData();

                        if ($referenceSchema instanceof DataObjectGeneratorSchemaInterface) {

                            foreach (array_keys($referenceSchema->getFlatStorage()) as $referenceKey) {

                                $storedManyObject->{$key . $referenceKey} = $referenceData[$referenceKey];

                            }

                        }

                    } else {

                        throw new ConfigurationException('Reference in related schema didn\'t have an associated data provider');

                    }

                } else {

                    $storedManyObject->{$key} = $data['related'][$key];

                }

                $storedManyObject->write();

            }

        }

    }
    /**
     * @param StorableInterface $object
     * @return mixed
     * @throws \Heystack\Subsystem\Core\Exception\ConfigurationException
     */
    public function write(StorableInterface $object)
    {

        $dataProviderIdentifier = $object->getStorableIdentifier();
        $schemaIdentifier = strtolower($object->getSchemaName());

        if ($this->hasDataProvider($dataProviderIdentifier)) {

            $dataProvider = $this->dataProviders[$dataProviderIdentifier];
            $schema = $this->generatorService->getSchema($schemaIdentifier);

            if ($schema instanceof DataObjectGeneratorSchemaInterface) {

                $storedObject = $this->writeStoredDataObject($schema, $dataProvider, $object);
                //CachedTransaction

                $this->writeStoredRelatedDataObject($schema, $storedObject);

                $this->eventService->dispatch(
                    self::IDENTIFIER . '.' . $object->getStorableIdentifier() . '.stored',
                    new Event($storedObject->ID)
                );

                return $storedObject;

            } else {

                throw new ConfigurationException('No schema found for identifier: ' . $schemaIdentifier);

            }

        } else {

            throw new ConfigurationException('Couldn\'t find data provider for identifier: ' . $dataProviderIdentifier);

        }

    }
}
