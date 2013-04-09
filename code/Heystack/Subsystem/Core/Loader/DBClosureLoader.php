<?php

namespace Heystack\Subsystem\Core\Loader;

use Closure;
use SQLQuery;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DBLoader
 * @package Heystack\Subsystem\Core\Loader
 */
class DBClosureLoader extends Loader
{
    /**
     * @var callable
     */
    protected $handler;
    /**
     * @param callable         $handler
     */
    public function __construct(Closure $handler)
    {
        $this->handler = $handler;
    }
    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     */
    public function load($resource, $type = null)
    {
        $handler = $this->handler;
        $builder = new \DataObject();
        $records = $builder->buildDataObjectSet($resource->execute());
        if ($records instanceof \DataObjectSet && count($records) > 0) {
            foreach ($records as $record) {
                $handler(
                    $record
                );
            }
        }
    }
    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return $resource instanceof SQLQuery;
    }

}