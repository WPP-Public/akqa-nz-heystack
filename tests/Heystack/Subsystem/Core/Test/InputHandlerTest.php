<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\Input\Handler;

class InputHandlerTest extends \PHPUnit_Framework_TestCase
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
        $processor = new TestInputProcessor('test_input_processor');
        $processor2 = new TestInputProcessor('test_input_processor2');
        $processor3 = new TestInputProcessor('test_input_processor3');

        $this->handler->addProcessor($processor);

        $this->assertEquals($processor, $this->handler->getProcessor('test_input_processor'));

        $this->assertEquals(false, $this->handler->getProcessor('fake'));

        $this->assertTrue($this->handler->hasProcessor('test_input_processor'));

        $this->assertFalse($this->handler->hasProcessor('fake'));

        $this->assertEquals(array(
            'test_input_processor' => $processor
                ), $this->handler->getProcessors());

        $this->handler->setProcessors(array(
            $processor2,
            $processor3
        ));

        $this->assertEquals(array(
            'test_input_processor2' => $processor2,
            'test_input_processor3' => $processor3,
                ), $this->handler->getProcessors());
    }

    public function testProcess()
    {
        $this->handler->addProcessor(new TestInputProcessor('test', 'oh yeah!'));

        $request = new \SS_HTTPRequest('GET', '/');

        $this->assertFalse($this->handler->process('fake', $request));

        $this->assertEquals('oh yeah!', $this->handler->process('test', $request));
    }

}
