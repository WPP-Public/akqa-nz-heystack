<?php

namespace Heystack\Core\Identifier;

/**
 * Class IdentifierInterface
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack\Core\Identifier
 */
interface IdentifierInterface
{
    /**
     * @return
     */
    public function getPrimary();

    /**
     * @return array
     */
    public function getSecondaries();

    /**
     * @param  IdentifierInterface $identifier
     * @return bool
     */
    public function isMatch(IdentifierInterface $identifier);

    /**
     * @param  IdentifierInterface $identifier
     * @return bool
     */
    public function isMatchStrict(IdentifierInterface $identifier);

    /**
     * Generate the full representation of the Identifier as a string
     * @return string
     */
    public function getFull();

    /**
     * Return the full representation of the Identifier as a string
     * @return string
     */
    public function __toString();
}
