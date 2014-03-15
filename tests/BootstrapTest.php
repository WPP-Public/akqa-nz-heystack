<?php

namespace Heystack\Core;

class BootstrapTest extends \PHPUnit_Framework_TestCase
{
    protected $container;
    
    public function setUp()
    {
        $this->container = $this->getMock(
            'Heystack\Core\DependencyInjection\SilverStripe\HeystackSilverStripeContainer'
        );
    }
    
    /**
     * @covers \Heystack\Core\Bootstrap::__construct
     */
    public function testCanConstructWithValidArguments()
    {
        $this->assertTrue(
            is_object(
                new Bootstrap($this->container)
            )
        );
    }

    /**
     * @covers \Heystack\Core\Bootstrap::__construct
     * @covers \Heystack\Core\Bootstrap::preRequest
     * @covers \Heystack\Core\Bootstrap::doBootstrap
     */
    public function testPreRequestDoesBootstrap()
    {
        $session = $this->getMock('Session');
        
        $sessionBackend = $this->getMock(
            'Heystack\Core\State\Backends\Session'
        );
        
        $eventDispatcher = $this->getMock(
            'Heystack\Core\EventDispatcher'
        );

        $sessionBackend
            ->expects($this->once())
            ->method('setSession')
            ->with($session);

        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(Events::PRE_REQUEST);
        
        $this->container
            ->expects($this->at(0))
            ->method('get')
            ->with(Services::BACKEND_SESSION)
            ->will($this->returnValue($sessionBackend));

        $this->container
            ->expects($this->at(1))
            ->method('get')
            ->with(Services::EVENT_DISPATCHER)
            ->will($this->returnValue($eventDispatcher));
        
        (new Bootstrap($this->container))->preRequest(
            $this->getMock('SS_HTTPRequest'),
            $this->getMock('Session'),
            $this->getMock('DataModel')
        );
    }

    /**
     * @covers \Heystack\Core\Bootstrap::__construct
     * @covers \Heystack\Core\Bootstrap::postRequest
     * @covers \Heystack\Core\Bootstrap::doBootstrap
     */
    public function testPostRequestDoesDispatch()
    {
        $session = $this->getMock('Session');

        $sessionBackend = $this->getMock(
            'Heystack\Core\State\Backends\Session'
        );

        $eventDispatcher = $this->getMock(
            'Heystack\Core\EventDispatcher'
        );

        $sessionBackend
            ->expects($this->once())
            ->method('setSession')
            ->with($session);

        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(Events::POST_REQUEST);

        $this->container
            ->expects($this->at(0))
            ->method('get')
            ->with(Services::BACKEND_SESSION)
            ->will($this->returnValue($sessionBackend));

        $this->container
            ->expects($this->at(1))
            ->method('get')
            ->with(Services::EVENT_DISPATCHER)
            ->will($this->returnValue($eventDispatcher));

        $b = new Bootstrap($this->container);
        $b->doBootstrap($this->getMock('Session'));
        $b->postRequest(
            $this->getMock('SS_HTTPRequest'),
            $this->getMock('SS_HTTPResponse'),
            $this->getMock('DataModel')
        );
    }
}