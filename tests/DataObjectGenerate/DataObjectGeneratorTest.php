<?php

namespace Heystack\Core\DataObjectGenerate;

use Heystack\Core\Identifier\Identifier;
use org\bovigo\vfs\vfsStream;

/**
 * @package Heystack\Core\Generate
 */
class DataObjectGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $root;
    /**
     * @var \Heystack\Core\DataObjectGenerate\DataObjectGenerator
     */
    protected $generator;
    /**
     * @var \Heystack\Core\DataObjectSchema\SchemaService
     */
    protected $schemaService;

    /**
     *
     */
    protected function setUp()
    {
        $this->root = vfsStream::setup('root');

        // Set up 
        $this->generator = new DataObjectGenerator(
            $this->schemaService = $this->getMock('Heystack\Core\DataObjectSchema\SchemaService'),
            vfsStream::url('root')
        );
    }

    /**
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::__construct
     */
    public function testCanConstruct()
    {
        $a = new DataObjectGenerator(
            $this->getMock('Heystack\Core\DataObjectSchema\SchemaService')
        );
        
        $this->assertTrue(is_object($a));
    }

    /**
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::process
     */
    public function testProcessCreatesDirectories()
    {
        $this->schemaService
            ->expects($this->once())
            ->method('getSchemas')
            ->will($this->returnValue([]));
        
        ob_start();
        $this->generator->process();
        ob_end_clean();
        
        $this->assertTrue(
            file_exists(vfsStream::url('root'))
        );

        $this->assertTrue(
            $this->root->hasChild('cache')
        );
    }

    /**
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::process
     */
    public function testProcessDeleteFiles()
    {
        vfsStream::create(
            [
                'cache' => [
                    'CachedSomething.php' => 'test'
                ]
            ],
            $this->root
        );
        
        $this->schemaService
            ->expects($this->once())
            ->method('getSchemas')
            ->will($this->returnValue([]));

        $this->assertTrue(
            file_exists(
                vfsStream::url('root/cache/CachedSomething.php')
            )
        );

        ob_start();
        $this->generator->process();
        ob_end_clean();
        
        $this->assertFalse(
            file_exists(
                vfsStream::url('root/cache/CachedSomething.php')
            )
        );
    }

    /**
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::process
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::writeDataObject
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::writeModelAdmin
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::getDataObjectSSViewer
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::getModelAdminSSViewer
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::processFlatStorage
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::processParentStorage
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::processChildStorage
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::isReference
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::beautify
     * @covers \Heystack\Core\DataObjectGenerate\DataObjectGenerator::output
     */
    public function testProcessMakesFiles()
    {
        $referenceSchema = $this->getMock('Heystack\Core\DataObjectSchema\SchemaInterface');

        $this->schemaService
            ->expects($this->once())
            ->method('getSchemas')
            ->will($this->returnValue([
                $schema = $this->getMock('Heystack\Core\DataObjectSchema\SchemaInterface')
            ]));

        $this->schemaService
            ->expects($this->once())
            ->method('hasSchema')
            ->with('extra')
            ->will($this->returnValue(true));
        
        $this->schemaService
            ->expects($this->once())
            ->method('getSchema')
            ->with('extra')
            ->will($this->returnValue($referenceSchema));

        $this->buildSchema(
            $schema,
            'Test',
            [
                'Test' => 'Text',
                'Extra' => '+Extra'
            ],
            [],
            []
        );

        $this->buildSchema(
            $referenceSchema,
            'Extra',
            [
                'Test' => 'Text'
            ]
        );
        
        ob_start();
        $this->generator->process();
        ob_end_clean();

        $this->assertTrue(
            file_exists(
                $cachedTestFile = vfsStream::url('root/cache/CachedTest.php')
            )
        );
        $this->assertTrue(
            file_exists(
                $storedTestFile = vfsStream::url('root/StoredTest.php')
            )
        );
        $this->assertTrue(
            file_exists(
                vfsStream::url('root/cache/GeneratedModelAdmin.php')
            )
        );
        
        $this->assertContains(
            'class CachedTest extends DataObject',
            file_get_contents($cachedTestFile)
        );

        $this->assertContains(
            'class StoredTest extends CachedTest',
            file_get_contents($storedTestFile)
        );
    }

    /**
     * @param $schema
     * @param $id
     * @param array $flat
     * @param array|bool $parent
     * @param array|bool $child
     */
    protected function buildSchema(
        $schema,
        $id,
        $flat = [],
        $parent = false,
        $child = false
    )
    {
        $schema->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue(new Identifier($id)));

        $schema->expects($this->once())
            ->method('getFlatStorage')
            ->will($this->returnValue($flat));
        
        if ($parent) {
            $schema->expects($this->once())
                ->method('getParentStorage')
                ->will($this->returnValue($parent));
        }
        
        if ($child) {

            $schema->expects($this->once())
                ->method('getChildStorage')
                ->will($this->returnValue($child));
        }
    }
}
