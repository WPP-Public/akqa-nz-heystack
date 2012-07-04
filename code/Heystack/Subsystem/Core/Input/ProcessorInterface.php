<?php

namespace Heystack\Subsystem\Core\Input;

interface ProcessorInterface
{

    public function getIdentifier();
    public function process(\SS_HTTPRequest $request);
}
