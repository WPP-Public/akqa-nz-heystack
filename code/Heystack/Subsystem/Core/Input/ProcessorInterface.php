<?php

namespace Heystack\Subsystem\Core\Input;

interface ProcessorInterface
{

    public function getName();
    public function process(\SS_HTTPRequest $request);

}
