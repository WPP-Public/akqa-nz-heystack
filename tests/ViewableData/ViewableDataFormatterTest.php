<?php

namespace Heystack\Core\ViewableData;


class ViewableDataFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\ViewableData\ViewableDataFormatter::__construct
     */
    public function testObjectCanBeConstructed()
    {
        $viewableData = $this->getMock(__NAMESPACE__.'\ViewableDataInterface');
        $this->assertTrue(
            is_object(
                $f = new ViewableDataFormatter($viewableData)
            )
        );
        $this->assertAttributeEquals($viewableData, 'obj', $f);
        
        return [$f, $viewableData];
    }

    /**
     * @covers \Heystack\Core\ViewableData\ViewableDataFormatter::__construct
     * @covers \Heystack\Core\ViewableData\ViewableDataFormatter::castingHelper
     * @covers \Heystack\Core\ViewableData\ViewableDataFormatter::hasMethod
     */
    public function testCastingHelper()
    {
        $viewableData = $this->getMock(__NAMESPACE__.'\ViewableDataInterface');
        $viewableData->expects($this->exactly(2))
            ->method('getCastings')
            ->will($this->returnValue([
                'Test' => 'Varchar'
            ]));
        
        $viewableDataFormatter = new ViewableDataFormatter($viewableData);
        
        $this->assertEquals('Varchar', $viewableDataFormatter->castingHelper('Test'));
        $this->assertEquals(false, $viewableDataFormatter->castingHelper('Nope'));
    }

    /**
     * @covers \Heystack\Core\ViewableData\ViewableDataFormatter::__construct
     * @covers \Heystack\Core\ViewableData\ViewableDataFormatter::__call
     */
    public function testMagicMethodCallWorks()
    {
        $viewableData = $this->getMockBuilder(__NAMESPACE__.'\ViewableDataInterface')
            ->setMethods(['getCastings', 'getDynamicMethods', 'getTest'])
            ->getMock();
        
        $viewableData->expects($this->any())
            ->method('getDynamicMethods')
            ->will($this->returnValue([]));

        $viewableData
            ->expects($this->once())
            ->method('getTest')
            ->with(true)
            ->will($this->returnValue('Hello'));

        $viewableDataFormatter = new ViewableDataFormatter($viewableData);
        
        $this->assertEquals('Hello', $viewableDataFormatter->Test(true));
    }

    /**
     * @depends testObjectCanBeConstructed
     * @covers \Heystack\Core\ViewableData\ViewableDataFormatter::getObj
     */
    public function testCanGetObject($args)
    {
        list($viewableDataFormattor, $viewableData) = $args;
        
        $this->assertEquals(
            $viewableData,
            $viewableDataFormattor->getObj()
        );
    }

//    /**
//     * @covers ViewableDataFormatter::__get
//     */
//    public function testMagicMethodGetWorks()
//    {
//        $viewableData = $this->getMockBuilder(__NAMESPACE__.'\ViewableDataInterface')
//            ->setMethods(['getCastings', 'getDynamicMethods', 'getTest'])
//            ->getMock();
//
//        $viewableData->expects($this->any())
//            ->method('getDynamicMethods')
//            ->will($this->returnValue([]));
//
//        $viewableData
//            ->expects($this->once())
//            ->method('getTest')
//            ->with(true)
//            ->will($this->returnValue('Hello'));
//
//        $viewableDataFormatter = new ViewableDataFormatter($viewableData);
//
//        $viewableData
//            ->expects($this->once())
//            ->method('getTest')
//            ->with(true)
//            ->will($this->returnValue('Hello'));
//        
//        $this->assertEquals('Hello', $viewableDataFormatter->Test);
//    }

//    public function tesMagicMethodSetWorks()
//    {
//        $this->object->Something = 'Yay';
//        $this->assertEquals('Yay', $this->object->Something);
//    }
//
//    /**
//     * @covers ViewableDataFormatter::hasMethod
//     */
//    public function testHasMethod()
//    {
//        $this->assertTrue($this->object->hasMethod('Test'));
//        $this->assertTrue($this->object->hasMethod('Test2'));
//        $this->assertFalse($this->object->hasMethod('Test3'));
//    }
//
//    public function testConstructor()
//    {
//        $formatter = new ViewableDataFormatter(
//            $viewableData = new TestViewableData(
//                [
//                    'Test' => 'Hello',
//                    'Test2' => 'Yo'
//                ],
//                [
//                    'Test' => 'Varchar',
//                    'Test2' => 'Varchar'
//                ]
//            )
//        );
//
//        $this->assertEquals($viewableData, $formatter->getObj());
//
//    }
}
