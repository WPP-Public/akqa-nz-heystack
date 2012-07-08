<?php

namespace Heystack\Subsystem\Core\Storage;

interface ProcessorInterface
{

    public function getName();
    public function process();

}
