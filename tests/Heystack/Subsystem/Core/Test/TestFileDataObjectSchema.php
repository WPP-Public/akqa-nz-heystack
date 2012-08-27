<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Generate\FileDataObjectSchema;

class TestFileDataObjectSchema extends FileDataObjectSchema
{
    
    

    protected function parseFile($file)
    {
        
        return unserialize($file);

    }

}
