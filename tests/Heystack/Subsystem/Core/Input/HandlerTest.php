<?php

namespace Heystack\Subsystem\Core\Input;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    public $handler;

    protected function setUp()
    {
        $this->handler = new Handler;
    }

    protected function tearDown()
    {
        $this->handler = null;
    }

    public function testProcessorHandlerTrait()
    {
        $processor = $this->getProcessorStub('test_input_processor');
        $processor2 = $this->getProcessorStub('test_input_processor2');
        $processor3 = $this->getProcessorStub('test_input_processor3');

        $this->handler->addProcessor($processor);

        $this->assertEquals($processor, $this->handler->getProcessor('test_input_processor'));

        $this->assertEquals(false, $this->handler->getProcessor('fake'));

        $this->assertTrue($this->handler->hasProcessor('test_input_processor'));

        $this->assertFalse($this->handler->hasProcessor('fake'));

        $this->assertEquals(
            [
                'test_input_processor' => $processor
            ],
            $this->handler->getProcessors()
        );

        $this->handler->setProcessors(
            [
                $processor2,
                $processor3
            ]
        );

        $this->assertEquals(
            [
                'test_input_processor2' => $processor2,
                'test_input_processor3' => $processor3,
            ],
            $this->handler->getProcessors()
        );
    }

    public function testProcess()
    {
        $this->handler->addProcessor($this->getProcessorStub('test', 'oh yeah!'));

        $request = new \SS_HTTPRequest('GET', '/');

        $this->assertFalse($this->handler->process('fake', $request));

        $this->assertEquals('oh yeah!', $this->handler->process('test', $request));
    }

    protected function getProcessorStub($identifier, $process = null)
    {
        $identifierStub = $this->getMock('Heystack\Subsystem\Core\Identifier\IdentifierInterface');
        $identifierStub->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue($identifier));

        $processorStub = $this->getMock('Heystack\Subsystem\Core\Input\ProcessorInterface');
        $processorStub->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($identifierStub));

        $processorStub->expects($this->any())
            ->method('process')
            ->will($this->returnValue($process));

        return $processorStub;
    }
}
