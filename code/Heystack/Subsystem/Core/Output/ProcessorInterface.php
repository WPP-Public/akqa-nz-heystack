<?php

namespace Heystack\Subsystem\Core\Output;

interface ProcessorInterface
{

    public function getIdentifier();
    public function process(\Controller $controller, $result = null);

}
