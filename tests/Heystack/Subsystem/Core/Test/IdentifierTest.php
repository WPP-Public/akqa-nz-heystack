<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Identifier\Identifier;

class IdentifierTest extends \PHPUnit_Framework_TestCase
{
    protected $identifier;

    protected function setUp()
    {
        $this->identifier = new Identifier(
            'test',
            array(
                'test'
            )
        );
    }

    protected function tearDown()
    {
        $this->identifier = null;
    }
    public function testIsMatch()
    {
        $stub = $this->getMock('Heystack\Subsystem\Core\Identifier\IdentifierInterface');
        $stub->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue('test'));

        $this->assertTrue(
            $this->identifier->isMatch($stub)
        );

        $stub = $this->getMock('Heystack\Subsystem\Core\Identifier\IdentifierInterface');
        $stub->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue('test2'));

        $this->assertFalse(
            $this->identifier->isMatch($stub)
        );
    }

    public function testIsMatchStrict()
    {
        $stub = $this->getMock('Heystack\Subsystem\Core\Identifier\IdentifierInterface');

        $stub->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue('test'));

        $stub->expects($this->any())
            ->method('getSubidentifiers')
            ->will($this->returnValue(array('test')));

        $this->assertTrue(
            $this->identifier->isMatchStrict($stub)
        );

        $stub = $this->getMock('Heystack\Subsystem\Core\Identifier\IdentifierInterface');

        $stub->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue('test'));

        $stub->expects($this->any())
            ->method('getSubidentifiers')
            ->will($this->returnValue(array('test2')));

        $this->assertFalse(
            $this->identifier->isMatchStrict($stub)
        );
    }
}
