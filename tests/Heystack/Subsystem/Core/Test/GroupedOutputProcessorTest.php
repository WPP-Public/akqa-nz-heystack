<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Output\GroupedProcessor;

class GroupedOutputProcessorTest extends \PHPUnit_Framework_TestCase
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

        $this->assertEquals('test', $this->groupedProcessor->getIdentifier());

        $processor = new TestOutputProcessor('test_input_processor');
        $processor2 = new TestOutputProcessor('test_input_processor2', 'sweet');
        $processor3 = new TestOutputProcessor('test_input_processor3', 'working');

        $this->groupedProcessor->setProcessors(array(
            $processor,
            $processor2,
            $processor3
        ));

        $this->assertEquals(null, $this->groupedProcessor->process(new \Controller()));

    }

}
