<?php

namespace Heystack\Core\Test;

use Heystack\Core\State\Backends\Session;

class SessionBackendTest extends \PHPUnit_Framework_TestCase
{

    protected $session;

    protected function setUp()
    {
        $_SESSION = [
            'HTTP_USER_AGENT' => ''
        ];
        $this->session = new Session(new \Session($_SESSION));
    }

    protected function tearDown()
    {
        $this->session = null;
    }

    public function testSessionNotStarted()
    {

        $_SESSION = null;

        $message = null;

        try {

            new Session(new \Session($_SESSION));

        } catch (\Exception $e) {

            $message = $e->getMessage();

        }

        $this->assertNotNull($message);

    }

    public function testSessionStarted()
    {

        $_SESSION = [
            'test' => 'yay'
        ];

        $session = new Session(new \Session($_SESSION));

        $this->assertEquals('yay', $session->getByKey('test'));

    }

    public function testSetGetByKey()
    {

        $this->session->setByKey('test', 'yay');

        $this->assertEquals('yay', $this->session->getByKey('test'));

    }

    public function testGetKeys()
    {

        $this->session->setByKey('test', 'yay');

        $this->assertEquals(['HTTP_USER_AGENT', 'test'], $this->session->getKeys());

    }

    public function testRemoveByKey()
    {

        $this->session->setByKey('test', 'yay');

        $this->session->removeByKey('test');

        $this->assertEquals(false, $this->session->getByKey('test'));

    }

    public function testRemoveAll()
    {

        $this->session->setByKey('test', 'yay');
        $this->session->setByKey('test2', 'yay');

        $this->session->removeAll();

        $this->assertEquals(false, $this->session->getByKey('test'));
        $this->assertEquals(false, $this->session->getByKey('test2'));

        $this->assertEquals(
            [
                'HTTP_USER_AGENT',
                'test',
                'test2'
            ],
            $this->session->getKeys()
        );

    }
}
