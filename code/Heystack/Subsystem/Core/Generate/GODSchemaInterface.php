<?php

namespace Heystack\Subsystem\Core\Generate;

interface GODSchemaInterface
{

    public function getIdentifier();
    public function getFlatStorage();
    public function getRelatedStorage();

}
