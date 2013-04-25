<?php

namespace Heystack\Subsystem\Core\Output;

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
        $processor = $this->getProcessorStub('test_output_processor');
        $processor2 = $this->getProcessorStub('test_output_processor2');
        $processor3 = $this->getProcessorStub('test_output_processor3');

        $this->handler->addProcessor($processor);

        $this->assertEquals($processor, $this->handler->getProcessor('test_output_processor'));

        $this->assertEquals(false, $this->handler->getProcessor('fake'));

        $this->assertTrue($this->handler->hasProcessor('test_output_processor'));

        $this->assertFalse($this->handler->hasProcessor('fake'));

        $this->assertEquals(
            array(
                'test_output_processor' => $processor
            ),
            $this->handler->getProcessors()
        );

        $this->handler->setProcessors(
            array(
                $processor2,
                $processor3
            )
        );

        $this->assertEquals(
            array(
                'test_output_processor2' => $processor2,
                'test_output_processor3' => $processor3,
            ),
            $this->handler->getProcessors()
        );
    }

    public function testProcess()
    {
        $this->handler->addProcessor($this->getProcessorStub('test', 'oh yeah!'));

        $controller = new \Controller();

        $this->assertFalse($this->handler->process('fake', $controller));

        $this->assertEquals('oh yeah!', $this->handler->process('test', $controller));
    }

    protected function getProcessorStub($identifier, $process = null)
    {
        $identifierStub = $this->getMock('Heystack\Subsystem\Core\Identifier\IdentifierInterface');
        $identifierStub->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue($identifier));

        $processorStub = $this->getMock('Heystack\Subsystem\Core\Output\ProcessorInterface');
        $processorStub->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($identifierStub));

        $processorStub->expects($this->any())
            ->method('process')
            ->will($this->returnValue($process));

        return $processorStub;
    }
}
