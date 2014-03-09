<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Core\Storage\Backends\SilverStripeOrm;

use Heystack\Core\Exception\ConfigurationException;
use Heystack\Core\Generate\DataObjectGenerator;
use Heystack\Core\Generate\DataObjectGeneratorSchemaInterface;
use Heystack\Core\Identifier\Identifier;
use Heystack\Core\Identifier\IdentifierInterface;
use Heystack\Core\Storage\BackendInterface;
use Heystack\Core\Storage\Event;
use Heystack\Core\Storage\StorableInterface;
use Heystack\Core\Traits\HasEventServiceTrait;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Backend
 * @package Heystack\Core\Storage\Backends\SilverStripeOrm
 * @author  Cam Spiers <cameron@heyday.co.nz>
 */
class Backend implements BackendInterface
{
    use HasEventServiceTrait;

    /**
     *
     */
    const IDENTIFIER = 'silverstripe_orm';
    /**
     * @var \Heystack\Core\Generate\DataObjectGenerator|null
     */
    private $generatorService;
    /**
     * @var \Heystack\Core\Storage\StorableInterface[]
     */
    private $referenceDataProviders = [];

    /**
     * @param EventDispatcher     $eventService
     * @param DataObjectGenerator $generatorService
     */
    public function __construct(
        EventDispatcher $eventService,
        DataObjectGenerator $generatorService
    )
    {
        $this->eventService = $eventService;
        $this->generatorService = $generatorService;
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

        $schema = $this->generatorService->getSchema($schemaIdentifier);

        if ($schema instanceof DataObjectGeneratorSchemaInterface) {

            $storedObject = $this->writeStoredDataObject($schema, $object);

            $this->eventService->dispatch(
                self::IDENTIFIER . '.' . $object->getStorableIdentifier() . '.stored',
                new Event($storedObject->ID)
            );

            return $storedObject;

        } else {

            throw new ConfigurationException('No schema found for identifier: ' . $schemaIdentifier);

        }

    }

    /**
     * @param  DataObjectGeneratorSchemaInterface              $schema
     * @param  StorableInterface                               $object
     * @return mixed
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
    protected function writeStoredDataObject(
        DataObjectGeneratorSchemaInterface $schema,
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

                $referenceSchema = $this->generatorService->getSchema($reference);
                $referenceSchemaIdentifier = $referenceSchema->getIdentifier();

                if ($this->hasReferenceDataProvider($referenceSchemaIdentifier)) {

                    if ($referenceSchema instanceof DataObjectGeneratorSchemaInterface) {

                        $referenceData = $this->getReferenceDataProvider($referenceSchemaIdentifier)->getStorableData();

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

                    $storedObject->$key = $writeableData['flat'][$key];

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
