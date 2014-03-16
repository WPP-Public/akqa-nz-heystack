<?php

namespace Heystack\Core\Console;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testCanConstructApplication()
    {
        $this->assertTrue(
            is_object($a = new Application())
        );
        
        $this->assertAttributeEquals(
            'Heystack',
            'name',
            $a
        );
    }
}