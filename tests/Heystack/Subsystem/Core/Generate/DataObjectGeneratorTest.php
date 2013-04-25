<?php

namespace Heystack\Subsystem\Core\Generate;

/**
 * Class DataObjectGeneratorTest
 * @package Heystack\Subsystem\Core\Generate
 */
class DataObjectGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    protected $state;
    /**
     * @var
     */
    protected $generator;
    /**
     * @var
     */
    protected $identifier;
    /**
     * @var
     */
    protected $schema;

    /**
     *
     */
    protected function setUp()
    {
        $this->state = $this->getMockBuilder('Heystack\Subsystem\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();

        $this->identifier = $this->getMock('Heystack\Subsystem\Core\Identifier\IdentifierInterface');

        $this->schema = $this->getMock('Heystack\Subsystem\Core\Generate\DataObjectGeneratorSchemaInterface');

        $this->schema->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($this->identifier));

        $this->generator = new DataObjectGenerator(
            $this->state
        );
    }

    /**
     *
     */
    public function testAddSchema()
    {
        $this->identifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('test'));

        $this->generator->addSchema($this->schema);

        $this->assertTrue($this->generator->hasSchema('test'));
    }

    /**
     *
     */
    public function testAddByReferenceSchema()
    {
        $this->identifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('test'));

        $this->generator->addSchema($this->schema, true);

        $this->assertTrue($this->generator->hasSchema('test'));
    }

    /**
     *
     */
    public function testForceAddSchema()
    {
        $this->identifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('test'));

        $this->generator->addSchema($this->schema);

        $this->assertTrue($this->generator->hasSchema('test'));

        $this->generator->addSchema($this->schema, false, true);

        $this->assertEquals($this->schema, $this->generator->getSchema('test'));
    }

    /**
     *
     */
    public function testMergeSchema()
    {
        $this->identifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('test'));

        $this->generator->addSchema($this->schema);
        $this->generator->addSchema($this->schema);

        $this->assertTrue($this->generator->hasSchema('test'));
    }

    /**
     *
     */
    public function testGetSchema()
    {
        $this->identifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('test'));

        $this->generator->addSchema($this->schema);

        $this->assertEquals($this->schema, $this->generator->getSchema('test'));

        $this->assertEquals(false, $this->generator->getSchema('blah'));
    }

    /**
     *
     */
    public function testIsReference()
    {
        $this->identifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('test'));

        $this->generator->addSchema($this->schema);

        $this->assertEquals('test', $this->generator->isReference('+test'));
        $this->assertFalse($this->generator->isReference('test'));
    }
    /**
     * @expectedException Heystack\Subsystem\Core\Exception\ConfigurationException
     */
    public function testIsReferenceException()
    {
        $this->identifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('test'));

        $this->generator->addSchema($this->schema);

        $this->generator->isReference('+test1');
    }
}