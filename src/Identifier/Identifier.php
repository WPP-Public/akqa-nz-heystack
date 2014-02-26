<?php

namespace Heystack\Core\Identifier;

/**
 * Class Identifier
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack\Core\Identifier
 */
class Identifier implements IdentifierInterface
{
    /**
     *
     */
    const GLUE = '.';
    /**
     * @var
     */
    protected $primary;
    /**
     * @var array
     */
    protected $secondaries = [];

    /**
     * @param string $primary
     * @param array $secondaries
     */
    public function __construct($primary, array $secondaries = null)
    {
        $this->primary = $primary;
        if (is_array($secondaries)) {
            $this->secondaries = $secondaries;
        }
    }

    /**
     * @return string
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @return array
     */
    public function getSecondaries()
    {
        return $this->secondaries;
    }

    /**
     * @return string
     */
    public function getFull()
    {
        if (count($this->secondaries)) {
            return implode(
                self::GLUE,
                array_merge(
                    [$this->primary],
                    $this->secondaries
                )
            );

        }

        return $this->primary;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFull();
    }

    /**
     * @param  IdentifierInterface $identifier
     * @return bool
     */
    public function isMatch(IdentifierInterface $identifier)
    {
        return $this->primary === $identifier->getPrimary();
    }

    /**
     * @param  IdentifierInterface $identifier
     * @return bool
     */
    public function isMatchStrict(IdentifierInterface $identifier)
    {
        return $this->isMatch($identifier) && $this->secondaries === $identifier->getSecondaries();
    }
}
