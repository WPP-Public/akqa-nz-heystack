<?php

namespace Heystack\Subsystem\Core\Identifier;

/**
 * Class Identifier
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack\Subsystem\Core\Identifier
 */
class Identifier implements IdentifierInterface
{
    /**
     * @var
     */
    protected $identifier;
    /**
     * @var array
     */
    protected $subidentifiers = array();
    /**
     * @param $identifier
     * @param array $subidentifiers
     */
    public function __construct($identifier, array $subidentifiers = null)
    {
        $this->identifier = $identifier;
        if (is_array($subidentifiers)) {
            $this->subidentifiers = $subidentifiers;
        }
    }
    /**
     * @return
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
    /**
     * @return array
     */
    public function getSubidentifiers()
    {
        return $this->subidentifiers;
    }
    /**
     * @param IdentifierInterface $identifier
     * @return bool
     */
    public function isMatch(IdentifierInterface $identifier)
    {
        return $this->identifier === $identifier->getIdentifier();
    }
    /**
     * @param IdentifierInterface $identifier
     * @return bool
     */
    public function isMatchStrict(IdentifierInterface $identifier)
    {
        return $this->isMatch($identifier) && $this->subidentifiers === $identifier->getSubidentifiers();
    }
}