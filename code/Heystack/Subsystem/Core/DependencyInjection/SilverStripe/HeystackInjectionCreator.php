<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * DependencyInjection namespace
 */
namespace Heystack\Subsystem\Core\DependencyInjection\SilverStripe;

use Symfony\Component\DependencyInjection\Container;

/**
 * Meant to replace SilverStripe's Injection Creator and allows services in the generated container
 * (Using Symfony's Dependency Injection) to be used in SilverStripe's Dependency Injection
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack
 *
 */
class HeystackInjectionCreator extends \InjectionCreator
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $heystackContainer;

    /**
     * @param Container $heystackContainer the generated container
     */
    public function __construct(Container $heystackContainer)
    {
        $this->heystackContainer = $heystackContainer;
    }

    /**
     * Creates or retrieves the service that is requested
     *
     * @param $class name of service
     * @param array $params
     * @return mixed|object
     * @throws \InvalidArgumentException
     */
    public function create($class, $params = [])
    {
        if (substr($class, 0, 9) === 'heystack.') {
            $class = substr($class, 9);
            if ($this->heystackContainer->has($class)) {
                return $this->heystackContainer->get($class);
            } elseif ($this->heystackContainer->hasParameter($class)) {
                return $this->heystackContainer->getParameter($class);
            } else {
                throw new \InvalidArgumentException(
                    sprintf(
                        "Requested Heystack service or parameter '%s' doesn't exist",
                        $class
                    )
                );
            }
        } else {
            return parent::create($class, $params);
        }
    }
}
