<?php

namespace Heystack\Subsystem\Core\Test;

class TestStateable implements \Serializable
{
    protected $data = array('test');

    public function setData($data)
    {
        $this->data = $data;
    }

    public function serialize()
    {
        return serialize($this->data);
    }

    public function unserialize($data)
    {
        $this->data = unserialize($data);
    }
}