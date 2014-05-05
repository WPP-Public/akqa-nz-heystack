<?php

namespace Heystack\Core\Storage\Backends\SilverStripeOrm;

use Heystack\Core\Exception\ConfigurationException;
use Heystack\Core\DataObjectGenerate\DataObjectGenerator;
use Heystack\Core\Identifier\Identifier;
use Heystack\Core\Identifier\IdentifierInterface;
use Heystack\Core\Storage\BackendInterface;
use Heystack\Core\Storage\Event;
use Heystack\Core\Storage\StorableInterface;
use Heystack\Core\DataObjectSchema\SchemaService;
use Heystack\Core\DataObjectSchema\SchemaInterface;
use Heystack\Core\Traits\HasEventServiceTrait;
use Heystack\Core\Traits\HasSchemaServiceTrait;
use Heystack\Core\Traits\HasGeneratorServiceTrait;
use Heystack\Core\EventDispatcher;

/**
 * Stores StorableInterfaces intro the SilverStripe database
 * 
 * @package Heystack\Core\Storage\Backends\SilverStripeOrm
 * @author  Cam Spiers <cameron@heyday.co.nz>
 */
class Backend implements BackendInterface
{
    use HasEventServiceTrait;
    use HasSchemaServiceTrait;
    use HasGeneratorServiceTrait;

    /**
     * The identifier for this backend
     */
    const IDENTIFIER = 'silverstripe_orm';

    /**
     * @var \Heystack\Core\Storage\StorableInterface[]
     */
    private $referenceDataProviders = [];

    /**
     * @param \Heystack\Core\EventDispatcher $eventService
     * @param \Heystack\Core\DataObjectGenerate\DataObjectGenerator $generatorService
     * @param \Heystack\Core\DataObjectSchema\SchemaService $schemaService
     */
    public function __construct(
        DataObjectGenerator $generatorService,
        SchemaService $schemaService,
        EventDispatcher $eventService
    )
    {
        $this->generatorService = $generatorService;
        $this->schemaService = $schemaService;
        $this->eventService = $eventService;
    }

    /**
     * @param \Heystack\Core\Storage\StorableInterface $referenceDataProvider
     */
    public function addReferenceDataProvider(StorableInterface $referenceDataProvider)
    {
        $this->referenceDataProviders[$referenceDataProvider->getStorableIdentifier()] = $referenceDataProvider;
    }

    /**
     * @param IdentifierInterface $identifier
     * @return \Heystack\Core\Storage\StorableInterface
     */
    public function getReferenceDataProvider(IdentifierInterface $identifier)
    {
        return $this->referenceDataProviders[$identifier->getFull()];
    }

    /**
     * @param IdentifierInterface $referenceDataProviderIdentifier
     * @return bool
     */
    public function hasReferenceDataProvider(IdentifierInterface $referenceDataProviderIdentifier)
    {
        return isset($this->referenceDataProviders[$referenceDataProviderIdentifier->getFull()]);
    }
    
    /**
     * @return string
     */
    public function getIdentifier()
    {
        return new Identifier(self::IDENTIFIER);
    }

    /**
     * @param  StorableInterface                               $object
     * @return mixed
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
    public function write(StorableInterface $object)
    {
        $schemaIdentifier = strtolower($object->getSchemaName());

        $storedObject = $this->writeStoredDataObject(
            $this->schemaService->getSchema($schemaIdentifier),
            $object
        );

        $this->eventService->dispatch(
            self::IDENTIFIER . '.' . $object->getStorableIdentifier() . '.stored',
            new Event($storedObject->ID)
        );

        return $storedObject;
    }

    /**
     * @param  SchemaInterface $schema
     * @param  StorableInterface $object
     * @return mixed
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
    protected function writeStoredDataObject(
        SchemaInterface $schema,
        StorableInterface $object
    )
    {
        $identifier = $schema->getIdentifier()->getFull();
        $saveable = sprintf('Stored%s', $identifier);
        $storedObject = \Injector::inst()->create($saveable);
        $writeableData = $object->getStorableData();

        foreach ($schema->getFlatStorage() as $key => $value) {

            $reference = $this->generatorService->isReference($value);

            if ($reference) {

                $referenceSchema = $this->schemaService->getSchema($reference);
                $referenceSchemaIdentifier = $referenceSchema->getIdentifier();

                if ($this->hasReferenceDataProvider($referenceSchemaIdentifier)) {

                    $referenceData = $this->getReferenceDataProvider($referenceSchemaIdentifier)->getStorableData();

                    foreach (array_keys($referenceSchema->getFlatStorage()) as $referenceKey) {

                        if (isset($referenceData['flat'][$referenceKey])) {

                            $storedObject->{$key . $referenceKey} = $referenceData['flat'][$referenceKey];

                        } else {

                            throw new ConfigurationException("No data found for key: $key on identifier: $reference");

                        }

                    }

                } else {

                    throw new ConfigurationException(
                        sprintf(
                            'Reference in flat schema didn\'t have an associated data provider for identifier `%s`' . 
                            ' available identifiers are `%s`',
                            $referenceSchema->getIdentifier()->getFull(),
                            implode(', ', array_keys($this->referenceDataProviders))
                        )
                    );

                }

            } else {

                if (array_key_exists($key, $writeableData['flat'])) {
                    $value = $writeableData['flat'][$key];

                    $storedObject->$key = $value;

                    if (!is_null($value) && !is_scalar($value)) {
                        throw new ConfigurationException(
                            "Non-scalar value found for key: $key on identifier: " . $object->getStorableIdentifier()
                        );
                    }

                } else {

                    throw new ConfigurationException(
                        "No data found for key: $key on identifier: " . $object->getStorableIdentifier()
                    );

                }

            }

        }

        if (isset($writeableData['parent']) && isset($writeableData['flat']['ParentID'])) {
            $storedObject->ParentID = $writeableData['flat']['ParentID'];
        }

        $storedObject->write();

        return $storedObject;
    }
}
