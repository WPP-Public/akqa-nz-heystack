<?php

namespace Heystack\Subsystem\Core\Output;

class GroupedProcessorTest extends \PHPUnit_Framework_TestCase
{
    public $groupedProcessor;

    protected function setUp()
    {
        $this->groupedProcessor = new GroupedProcessor('test');
    }

    protected function tearDown()
    {
        $this->groupedProcessor = null;
    }

    public function testProcess()
    {
        $this->assertEquals('test', $this->groupedProcessor->getIdentifier()->getFull());

        $this->groupedProcessor->setProcessors(
            array(
                $this->getProcessorStub('test_output_processor'),
                $this->getProcessorStub('test_output_processor2', 'sweet'),
                $this->getProcessorStub('test_output_processor3', 'working')
            )
        );

        $this->assertEquals(null, $this->groupedProcessor->process(new \Controller()));
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
