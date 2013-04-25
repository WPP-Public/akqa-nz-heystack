<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Input\GroupedProcessor;

class GroupedInputProcessorTest extends \PHPUnit_Framework_TestCase
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
                $this->getProcessorStub('test_input_processor'),
                $this->getProcessorStub('test_input_processor2', 'sweet'),
                $this->getProcessorStub('test_input_processor3', 'working')
            )
        );

        $results = $this->groupedProcessor->process(new \SS_HTTPRequest('GET', '/'));

        $this->assertEquals(
            array(
                'test_input_processor'  => '',
                'test_input_processor2' => 'sweet',
                'test_input_processor3' => 'working'
            ),
            $results
        );

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
