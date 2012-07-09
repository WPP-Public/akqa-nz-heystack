<?php

namespace Heystack\Subsystem\Core\Storage;

interface ProcessorInterface
{

    public function getIdentifier();
    public function process($dataobject);

}
