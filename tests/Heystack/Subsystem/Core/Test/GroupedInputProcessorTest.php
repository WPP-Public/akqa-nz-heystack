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

        $this->assertEquals('test', $this->groupedProcessor->getIdentifier()->getPrimary());

        $processor = new TestInputProcessor('test_input_processor');
        $processor2 = new TestInputProcessor('test_input_processor2', 'sweet');
        $processor3 = new TestInputProcessor('test_input_processor3', 'working');

        $this->groupedProcessor->setProcessors(array(
            $processor,
            $processor2,
            $processor3
        ));

        $results = $this->groupedProcessor->process(new \SS_HTTPRequest('GET', '/'));

        $this->assertEquals(array(
            'test_input_processor' => '',
            'test_input_processor2' => 'sweet',
            'test_input_processor3' => 'working'
        ), $results);

    }

}
