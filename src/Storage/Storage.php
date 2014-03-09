<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Core\Storage;

/**
 *
 * Stores objects that implement StorableInterface using backends
 *
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
/**
 * Class Storage
 * @package Heystack\Core\Storage
 */
class Storage
{

    /**
     * @var \\Heystack\Core\Storage\BackendInterface[]
     */
    private $backends = [];

    /**
     * @param array $backends
     */
    public function __construct(array $backends = null)
    {
        if (!is_null($backends)) {
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
     * @param  StorableInterface $object
     * @return array
     * @throws \Exception
     */
    public function process(StorableInterface $object)
    {
        if (is_array($this->backends) && count($this->backends) > 0) {

            $results = [];

            $identifiers = $object->getStorableBackendIdentifiers();

            $backends = count($identifiers) == 0 ? $this->backends : array_intersect_key(
                $this->backends,
                array_flip($identifiers)
            );

            foreach ($backends as $identifier => $backend) {
                $results[$identifier] = $backend->write($object);
            }

            return $results;

        } else {
            throw new \Exception('Tried to process an storable object with no backends');
        }
    }
}
