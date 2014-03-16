<?php

namespace Heystack\Core\State\Backends;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\State\Backends\Session::setSession
     * @covers \Heystack\Core\State\Backends\Session::getSession
     */
    public function testSetGetSession()
    {
        $session = new Session();
        $session->setSession($s = new \Session([]));
        $this->assertEquals($s, $session->getSession());
    }

    /**
     * @covers \Heystack\Core\State\Backends\Session::setSession
     * @covers \Heystack\Core\State\Backends\Session::getByKey
     */
    public function testGetByKey()
    {
        $session = new Session();
        $session->setSession(new \Session(['test' => 'yay']));
        $this->assertEquals(
            'yay',
            $session->getByKey('test')
        );
        return $session;
    }

    /**
     * @covers \Heystack\Core\State\Backends\Session::setSession
     * @covers \Heystack\Core\State\Backends\Session::setByKey
     * @covers \Heystack\Core\State\Backends\Session::getByKey
     */
    public function testSetByKey()
    {
        $session = new Session();
        $session->setSession(new \Session([]));
        $session->setByKey('test', 'hello');
        $this->assertEquals(
            'hello',
            $session->getByKey('test')
        );
    }

    /**
     * @covers \Heystack\Core\State\Backends\Session::setSession
     * @covers \Heystack\Core\State\Backends\Session::getKeys
     * @covers \Heystack\Core\State\Backends\Session::setByKey
     */
    public function testGetKeys()
    {
        $session = new Session();
        $session->setSession(new \Session([]));
        $session->setByKey('test', 'hello');
        $this->assertEquals(
            ['test'],
            $session->getKeys()
        );
    }

    /**
     * @covers \Heystack\Core\State\Backends\Session::setSession
     * @covers \Heystack\Core\State\Backends\Session::getByKey
     * @covers \Heystack\Core\State\Backends\Session::removeByKey
     */
    public function testRemoveByKey()
    {
        $session = new Session();
        $session->setSession(new \Session(['test' => 'hello']));
        $session->removeByKey('test', 'hello');
        $this->assertNull(
            $session->getByKey('test')
        );
    }

    /**
     * @covers \Heystack\Core\State\Backends\Session::setSession
     * @covers \Heystack\Core\State\Backends\Session::getKeys
     * @covers \Heystack\Core\State\Backends\Session::removeAll
     * @covers \Heystack\Core\State\Backends\Session::removeByKey
     */
    public function testRemoveAll()
    {
        $session = new Session();
        $session->setSession(new \Session(['test' => 'hello']));
        $session->removeAll();
        $this->assertNull(
            $session->getByKey('test')
        );
    }
}
