<?php

namespace Heystack\Subsystem\Core\Output;

interface ProcessorInterface
{

    public function getName();
    public function process();

}
