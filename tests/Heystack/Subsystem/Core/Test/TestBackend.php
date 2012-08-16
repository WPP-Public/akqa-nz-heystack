<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\State\BackendInterface;

class TestBackend implements BackendInterface
{
    protected $storage = array();

    public function setByKey($key, $var)
    {

        $this->storage[$key] = $var;

    }

    public function getByKey($key)
    {

        return isset($this->storage[$key]) ? $this->storage[$key] : null;

    }

    public function removeByKey($key)
    {

        unset($this->storage[$key]);

    }

    public function removeAll(array $exclude = array())
    {
        foreach ($this->storage as $key => $value) {

            if (!in_array($key, $exclude)) {
                unset($this->storage[$key]);
            }
        }
    }
    
    public function getKeys()
    {
        
        return array_keys($this->storage);
        
    }
    
}