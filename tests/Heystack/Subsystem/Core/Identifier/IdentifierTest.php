<?php

namespace Heystack\Core\Identifier;

class IdentifierTest extends \PHPUnit_Framework_TestCase
{
    protected $identifier;

    protected function setUp()
    {
        $this->identifier = new Identifier(
            'test',
            [
                'test'
            ]
        );
    }

    protected function tearDown()
    {
        $this->identifier = null;
    }
    public function testIsMatch()
    {
        $stub = $this->getMock('Heystack\Core\Identifier\IdentifierInterface');
        $stub->expects($this->any())
            ->method('getPrimary')
            ->will($this->returnValue('test'));

        $this->assertTrue(
            $this->identifier->isMatch($stub)
        );

        $stub = $this->getMock('Heystack\Core\Identifier\IdentifierInterface');
        $stub->expects($this->any())
            ->method('getPrimary')
            ->will($this->returnValue('test2'));

        $this->assertFalse(
            $this->identifier->isMatch($stub)
        );
    }

    public function testIsMatchStrict()
    {
        $stub = $this->getMock('Heystack\Core\Identifier\IdentifierInterface');

        $stub->expects($this->any())
            ->method('getPrimary')
            ->will($this->returnValue('test'));

        $stub->expects($this->any())
            ->method('getSecondaries')
            ->will($this->returnValue(['test']));

        $this->assertTrue(
            $this->identifier->isMatchStrict($stub)
        );

        $stub = $this->getMock('Heystack\Core\Identifier\IdentifierInterface');

        $stub->expects($this->any())
            ->method('getPrimary')
            ->will($this->returnValue('test'));

        $stub->expects($this->any())
            ->method('getSecondaries')
            ->will($this->returnValue(['test2']));

        $this->assertFalse(
            $this->identifier->isMatchStrict($stub)
        );
    }
}
