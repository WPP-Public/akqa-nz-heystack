<?php

namespace Heystack\Core\Identifier;

class IdentifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Heystack\Core\Identifier\Identifier
     */
    protected $identifierWithSecondaries;
    
    /**
     * @var \Heystack\Core\Identifier\Identifier
     */
    protected $identifierWithPrimary;

    protected function setUp()
    {
        $this->identifierWithSecondaries = new Identifier(
            'test',
            [
                'test'
            ]
        );
        
        $this->identifierWithPrimary = new Identifier('test');
    }

    /**
     * @covers \Heystack\Core\Identifier\Identifier::__construct
     * @covers \Heystack\Core\Identifier\Identifier::isMatch
     */
    public function testIsMatch()
    {
        $stub = $this->getMock('Heystack\Core\Identifier\IdentifierInterface');
        $stub->expects($this->any())
            ->method('getPrimary')
            ->will($this->returnValue('test'));

        $this->assertTrue(
            $this->identifierWithSecondaries->isMatch($stub)
        );

        $stub = $this->getMock('Heystack\Core\Identifier\IdentifierInterface');
        $stub->expects($this->any())
            ->method('getPrimary')
            ->will($this->returnValue('test2'));

        $this->assertFalse(
            $this->identifierWithSecondaries->isMatch($stub)
        );
    }

    /**
     * @covers \Heystack\Core\Identifier\Identifier::__construct
     * @covers \Heystack\Core\Identifier\Identifier::isMatchStrict
     */
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
            $this->identifierWithSecondaries->isMatchStrict($stub)
        );

        $stub = $this->getMock('Heystack\Core\Identifier\IdentifierInterface');

        $stub->expects($this->any())
            ->method('getPrimary')
            ->will($this->returnValue('test'));

        $stub->expects($this->any())
            ->method('getSecondaries')
            ->will($this->returnValue(['test2']));

        $this->assertFalse(
            $this->identifierWithSecondaries->isMatchStrict($stub)
        );
    }

    /**
     * @covers \Heystack\Core\Identifier\Identifier::__construct
     * @covers \Heystack\Core\Identifier\Identifier::getPrimary
     */
    public function testGetPrimary()
    {
        $this->assertEquals(
            'test',
            $this->identifierWithSecondaries->getPrimary()
        );
    }

    /**
     * @covers \Heystack\Core\Identifier\Identifier::__construct
     * @covers \Heystack\Core\Identifier\Identifier::getSecondaries
     */
    public function testGetSecondaries()
    {
        $this->assertEquals(
            ['test'],
            $this->identifierWithSecondaries->getSecondaries()
        );
    }

    /**
     * @covers \Heystack\Core\Identifier\Identifier::__construct
     * @covers \Heystack\Core\Identifier\Identifier::getFull
     */
    public function testGetFull()
    {
        $this->assertEquals(
            'test.test',
            $this->identifierWithSecondaries->getFull()
        );
    }

    /**
     * @covers \Heystack\Core\Identifier\Identifier::__construct
     * @covers \Heystack\Core\Identifier\Identifier::getFull
     * @covers \Heystack\Core\Identifier\Identifier::__toString
     */
    public function testGetFullWithOnlyPrimary()
    {
        $this->assertEquals(
            'test',
            $this->identifierWithPrimary->getFull()
        );
    }

    /**
     * @covers \Heystack\Core\Identifier\Identifier::__construct
     * @covers \Heystack\Core\Identifier\Identifier::getFull
     * @covers \Heystack\Core\Identifier\Identifier::__toString
     */
    public function testToString()
    {
        $this->assertEquals(
            'test.test',
            (string) $this->identifierWithSecondaries
        );
    }
}
