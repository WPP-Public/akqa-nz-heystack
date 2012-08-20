<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Generate\DataObjectGeneratorSchemaInterface;

class TestDataObjectSchema implements DataObjectGeneratorSchemaInterface
{

    public function getChildStorage()
    {
        
        return array();
        
    }

    public function getDataProviderIdentifier()
    {
        
        return 'test';
        
    }

    public function getFlatStorage()
    {
        
        return array(
            'Test' => 'Text'
        );
        
    }

    public function getIdentifier()
    {
        
        return 'test';
        
    }

    public function getParentStorage()
    {
        
        return array();
        
    }

    public function getRelatedStorage()
    {
        
        return array();
        
    }

    public function mergeSchema(DataObjectGeneratorSchemaInterface $schema)
    {
        
        return false;
        
    }

}
