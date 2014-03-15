<?php

namespace Heystack\Core\Storage;

use Heystack\Core\Identifier\Identifier;

class StorageTest extends \PHPUnit_Framework_TestCase
{
    const MOCK_IDENTIFIER = 'test';

    /**
     * @var \Heystack\Core\Storage\BackendInterface
     */
    protected $mockBackend;

    protected function setUp()
    {
        $this->mockBackend = $this->getMock('Heystack\Core\Storage\BackendInterface');
        $this->mockBackend->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue(new Identifier(self::MOCK_IDENTIFIER)));
    }

    /**
     * @covers \Heystack\Core\Storage\Storage::__construct
     */
    public function testObjectCanBeConstructedWithNoArguments()
    {
        $this->assertTrue(
            is_object($s = new Storage())
        );
        
        return $s;
    }

    /**
     * @covers \Heystack\Core\Storage\Storage::__construct
     * @covers \Heystack\Core\Storage\Storage::setBackends
     */
    public function testObjectCanBeConstructedWithBackends()
    {
        $this->assertTrue(
            is_object($s = new Storage([$this->mockBackend]))
        );
        
        return $s;
    }

    /**
     * @covers \Heystack\Core\Storage\Storage::getBackends
     * @depends testObjectCanBeConstructedWithBackends
     */
    public function testCanGetBackends(Storage $s)
    {
        $this->assertEquals(
            [self::MOCK_IDENTIFIER => $this->mockBackend],
            $s->getBackends()
        );
    }

    /**
     * @covers \Heystack\Core\Storage\Storage::addBackend
     * @covers \Heystack\Core\Storage\Storage::getBackends
     * @depends testObjectCanBeConstructedWithNoArguments
     */
    public function testCanAddBackend(Storage $s)
    {
        $s->addBackend($this->mockBackend);
        $this->assertEquals(
            [self::MOCK_IDENTIFIER => $this->mockBackend],
            $s->getBackends()
        );
    }

    /**
     * @covers \Heystack\Core\Storage\Storage::process
     * @depends testObjectCanBeConstructedWithNoArguments
     */
    public function testCanProcessStorableInterface(Storage $s)
    {
        $storable = $this->getMock(
            'Heystack\Core\Storage\StorableInterface'
        );

        $storable->expects($this->once())
            ->method('getStorableBackendIdentifiers')
            ->will($this->returnValue([self::MOCK_IDENTIFIER]));

        $mockBackend = $this->getMock('Heystack\Core\Storage\BackendInterface');
        $mockBackend->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue(new Identifier(self::MOCK_IDENTIFIER)));
        
        $mockBackend
            ->expects($this->once())
            ->method('write')
            ->with($storable)
            ->will($this->returnValue(
                $do = $this->getMock('DataObject')
            ));

        $s->addBackend($mockBackend);
        
        $results = $s->process($storable);
        
        $this->assertTrue(isset($results[self::MOCK_IDENTIFIER]));
        $this->assertEquals($do, $results[self::MOCK_IDENTIFIER]);
    }

    /**
     * @covers \Heystack\Core\Storage\Storage::__construct
     * @covers \Heystack\Core\Storage\Storage::process
     * @expectedException \Heystack\Core\Storage\Exception\StorageProcessingException
     */
    public function testExceptionThrownDuringProcessingWithNoBackends()
    {
        (new Storage())->process(
            $this->getMock('Heystack\Core\Storage\StorableInterface')
        );
    }
}
