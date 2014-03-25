<?php

namespace Heystack\Core\DataObjectSchema;

use org\bovigo\vfs\vfsStream;

class FileDataObjectSchemaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAbstractSchemaMock()
    {
        return $this->getMockBuilder(__NAMESPACE__ . '\FileDataObjectSchema')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCacheMock($methods = [])
    {
        return $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }

    /**
     * @covers \Heystack\Core\DataObjectSchema\FileDataObjectSchema::__construct
     * @expectedException \Heystack\Core\Exception\ConfigurationException
     * @expectedExceptionMessage Your config is empty for file
     */
    public function testExceptionThrownWhenConfigEmpty()
    {
        $mock = $this->getAbstractSchemaMock();
        $cache = $this->getCacheMock(['fetch']);
        $cache->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue(null));
        $mock->__construct('', $cache);
    }

    /**
     * @covers \Heystack\Core\DataObjectSchema\FileDataObjectSchema::__construct
     * @expectedException \Heystack\Core\Exception\ConfigurationException
     * @expectedExceptionMessage Identifier missing for file
     */
    public function testExceptionThrownWhenIdNotPresent()
    {
        $mock = $this->getAbstractSchemaMock();
        $cache = $this->getCacheMock(['fetch']);
        $cache->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue([]));
        $mock->__construct('', $cache);
    }

    /**
     * @covers \Heystack\Core\DataObjectSchema\FileDataObjectSchema::__construct
     * @expectedException \Heystack\Core\Exception\ConfigurationException
     * @expectedExceptionMessage Flat config missing for file
     */
    public function testExceptionThrownWhenFlatConfigNotPresent()
    {
        $mock = $this->getAbstractSchemaMock();
        $cache = $this->getCacheMock(['fetch']);
        $cache->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue([
                'id' => 'test'
            ]));
        $mock->__construct('', $cache);
    }

    /**
     * @covers \Heystack\Core\DataObjectSchema\FileDataObjectSchema::__construct
     */
    public function testCanConstructWithParseAndFileRead()
    {
        vfsStream::setup();
        vfsStream::create([
            'file' => 'content'
        ]);
        
        $fileUrl = vfsStream::url('root/file');
        $md5 = md5('content');
        
        $mock = $this->getAbstractSchemaMock();
        $mock->expects($this->once())
            ->method('parseFile')
            ->with($fileUrl)
            ->will($this->returnValue($config = [
                'id' => 'test',
                'flat' => [
                    'Test' => 'Text'
                ],
                'children' => [
                    'Tests'  => '+Test'
                ]
            ]));
        
        $cache = $this->getCacheMock(['fetch', 'save']);
        $cache->expects($this->once())
            ->method('fetch')
            ->with($md5)
            ->will($this->returnValue(false));

        $cache->expects($this->once())
            ->method('save')
            ->with($md5, $config);
        
        
        $mock->__construct(
            vfsStream::url('root/file'),
            $cache
        );

        return $mock;
    }

    /**
     * @covers \Heystack\Core\DataObjectSchema\FileDataObjectSchema::__construct
     */
    public function testCanConstructWithParse()
    {
        $md5 = md5('content');

        $mock = $this->getAbstractSchemaMock();
        $mock->expects($this->once())
            ->method('parseFile')
            ->with('content')
            ->will($this->returnValue($config = [
                'id' => 'test',
                'flat' => [
                    'Test2' => 'Text'
                ],
                'children' => [
                    'Tests2'  => '+Test2'
                ]
            ]));

        $cache = $this->getCacheMock(['fetch', 'save']);
        $cache->expects($this->once())
            ->method('fetch')
            ->with($md5)
            ->will($this->returnValue(false));

        $cache->expects($this->once())
            ->method('save')
            ->with($md5, $config);


        $mock->__construct(
            'content',
            $cache
        );
        
        return $mock;
    }

    /**
     * @depends testCanConstructWithParseAndFileRead
     * @depends testCanConstructWithParse
     * @covers \Heystack\Core\DataObjectSchema\FileDataObjectSchema::mergeSchema
     */
    public function testMergeSchema(FileDataObjectSchema $a, FileDataObjectSchema $b)
    {
        $a->mergeSchema($b);

        $this->assertEquals([
            'Test'  => 'Text',
            'Test2' => 'Text'
        ], $a->getFlatStorage());

        $this->assertEquals([
            'Tests'  => '+Test',
            'Tests2' => '+Test2'
        ], $a->getChildStorage());
    }
}
