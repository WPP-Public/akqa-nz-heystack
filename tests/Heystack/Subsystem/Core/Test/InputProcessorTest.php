<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Generate\Input\Processor;
use Heystack\Subsystem\Core\Generate\DataObjectGenerator;
use Heystack\Subsystem\Core\State\State;
use Symfony\Component\EventDispatcher\EventDispatcher;

class InputProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DataObjectGenerator
     */
    protected $generatorService;
    /**
     * @var Processor
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

        $this->generatorService = new DataObjectGenerator(new State(new TestBackend(), new EventDispatcher()));
        $this->object = new Processor($this->generatorService);

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

        $this->generatorService = null;
        $this->object = null;
    }

    /**
     * @covers Heystack\Subsystem\Core\Generate\Input\Processor::getGeneratorService
     */
    public function testGetGeneratorService()
    {
        $this->assertEquals($this->generatorService, $this->object->getGeneratorService());
    }

    /**
     * @covers Heystack\Subsystem\Core\Generate\Input\Processor::getIdentifier
     */
    public function testGetIdentifier()
    {
        $this->assertEquals(Processor::IDENTIFIER, $this->object->getIdentifier());
    }

    /**
     * @covers Heystack\Subsystem\Core\Generate\Input\Processor::process
     * @todo   Implement testProcess().
     */
    public function testProcess()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
