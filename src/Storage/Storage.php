<?php

namespace Heystack\Core\Storage;

use Heystack\Core\Storage\Exception\StorageProcessingException;

/**
 * Stores objects that implement StorableInterface using backends
 * 
 * Mutiple backends can be provider that implement the BackendInterface
 * These backends are expected to be able to write in some manny to storage
 * 
 * This could be a database or an API
 * 
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack\Core\Storage
 */
class Storage
{
    /**
     * @var \Heystack\Core\Storage\BackendInterface[]
     */
    private $backends = [];

    /**
     * @param array|void $backends
     */
    public function __construct(array $backends = null)
    {
        if (is_array($backends)) {
            $this->setBackends($backends);
        }
    }

    /**
     * @param \Heystack\Core\Storage\BackendInterface $backend
     */
    public function addBackend(BackendInterface $backend)
    {
        $this->backends[$backend->getIdentifier()->getFull()] = $backend;
    }

    /**
     * @param \Heystack\Core\Storage\BackendInterface[] $backends
     */
    public function setBackends(array $backends)
    {
        foreach ($backends as $backend) {
            $this->addBackend($backend);
        }
    }

    /**
     * @return \Heystack\Core\Storage\BackendInterface[]
     */
    public function getBackends()
    {
        return $this->backends;
    }

    /**
     * Runs through each storage backend and processes the Storable object
     * @param  StorableInterface $object
     * @return array
     * @throws \Exception
     */
    public function process(StorableInterface $object)
    {
        if (is_array($this->backends) && count($this->backends) > 0) {
            $results = [];

            $identifiers = $object->getStorableBackendIdentifiers();

            foreach ($this->backends as $identifier => $backend) {
                if (in_array($identifier, $identifiers)) {
                    $results[$identifier] = $backend->write($object);
                }
            }

            return $results;
        } else {
            throw new StorageProcessingException(
                sprintf(
                    "Tried to process an storable object '%s' with no backends",
                    $object->getStorableIdentifier()
                )
            );
        }
    }
}
