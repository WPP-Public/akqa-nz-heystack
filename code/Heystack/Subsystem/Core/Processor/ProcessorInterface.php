<?php

namespace Heystack\Subsystem\Core\Processor;

interface ProcessorInterface
{

    public function getName();
    public function process(\SS_HTTPRequest $request);

}
