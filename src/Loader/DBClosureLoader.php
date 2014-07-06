<?php

namespace Heystack\Core\Loader;

use Closure;
use DataList;
use Symfony\Component\Config\Loader\Loader;

/**
 * Used to process datalists via callback
 * @package Heystack\Core\Loader
 */
class DBClosureLoader extends Loader
{
    /**
     * @var callable
     */
    protected $handler;

    /**
     * @param callable $handler
     */
    public function __construct(Closure $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Loads a resource.
     *
     * @param  mixed                     $resource The resource
     * @param  string                    $type     The resource type
     * @throws \InvalidArgumentException
     */
    public function load($resource, $type = null)
    {
        $handler = $this->handler;
        if (is_array($resource)) {
            $rows = \DB::query(sprintf(
                "SELECT %s FROM `%s` %s",
                $resource[0],
                $resource[1],
                isset($resource[2]) ? "WHERE {$resource[2]}" : ''
            ));
            foreach ($rows as $index => $record) {
                if (empty($record['ClassName'])) {
                    throw new \RuntimeException("No classname in db record");
                }
                $handler(new $record['ClassName']($record), $index);
            }
        } else {
            throw new \InvalidArgumentException('Resource provided to DBClosureLoader is not an array');
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
        return $resource instanceof DataList;
    }

}
