<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Storage namespace
 */
namespace Heystack\Subsystem\Core\Storage;

/**
 *
 * Stores objects that implement StorableInterface using backends
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class Storage
{

    private $backends = array();

    public function __construct(array $backends = null)
    {

        if (!is_null($backends)) {

            $this->setBackends($backends);

        }

    }

    public function addBackend(BackendInterface $backend)
    {

        $this->backends[$backend->getIdentifier()] = $backend;

    }

    public function setBackends(array $backends)
    {

        foreach ($backends as $backend) {

            $this->addBackend($backend);

        }

    }
    
    public function getBackends()
    {
        return $this->backends;
    }
    
    public function process(StorableInterface $object)
    {

        if (is_array($this->backends) && count($this->backends) > 0) {

            $results = array();

            $identifiers = $object->getStorableBackendIdentifiers();

            $backends = count($identifiers) == 0 ? $backends : array_intersect_key($this->backends, array_flip($identifiers));

            foreach ($backends as $identifier => $backend) {

                $results[$identifier] = $backend->write($object);

            }

            return $results;

        } else {

            throw new \Exception('Tried to process an storable object with no backends');

        }

    }

}
