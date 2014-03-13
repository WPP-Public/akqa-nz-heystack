<?php

namespace Heystack\Core\Storage;

use Heystack\Core\Identifier\IdentifierInterface;

/**
 * The interface the storage backends need to implement
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
interface BackendInterface
{
    /**
     * @param  StorableInterface $object
     * @return mixed
     */
    public function write(StorableInterface $object);

    /**
     * @return \Heystack\Core\Identifier\IdentifierInterface
     */
    public function getIdentifier();

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
     * @return bool
     */
    public function hasReferenceDataProvider(IdentifierInterface $identifier);

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
     * @return \Heystack\Core\Storage\StorableInterface
     */
    public function getReferenceDataProvider(IdentifierInterface $identifier);

    /**
     * @param \Heystack\Core\Storage\StorableInterface $referenceDataProvider
     * @return void
     */
    public function addReferenceDataProvider(StorableInterface $referenceDataProvider);
}
