<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Input\Handler;

class CliInputControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \CliInputController
     */
    protected $object;

    protected $inputHandler;

    protected $processStub;

    protected function setUp()
    {

        $this->inputHandler = new Handler;

        $this->identifierStub = $this->getMock('Heystack\Subsystem\Core\Identifier\IdentifierInterface');
        $this->identifierStub->expects($this->any())
            ->method('getPrimary')
            ->will($this->returnValue('test_input_processor'));

        $this->processStub = $this->getMock('Heystack\Subsystem\Core\Input\ProcessorInterface');
        $this->processStub->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($this->identifierStub));

        $this->processStub->expects($this->any())
            ->method('process')
            ->will($this->returnValue('Hello'));

        $this->inputHandler->addProcessor($this->processStub);

        $this->object = new \CliInputController;
        $this->object->setInputHandlerService($this->inputHandler);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->object = null;
        $this->inputHandler = null;
    }

    /**
     * @covers CliInputController::process
     */
    public function testProcess()
    {
        $request = new \SS_HTTPRequest('GET', 'process/test_input_processor');
        $request->match('$Action/$Processor/$ID/$OtherID/$ExtraID');
        $this->assertEquals('Hello', $this->object->handleRequest($request)->getBody());
    }

    public function testConstruct()
    {

        $controller = new \CliInputController;
        $this->assertInternalType('object', $controller);

    }

    public function testSetGetInputHandlerService()
    {
        $controller = new \CliInputController;

        $controller->setInputHandlerService($this->inputHandler);

        $this->assertEquals($this->inputHandler, $controller->getInputHandlerService());
    }
}
