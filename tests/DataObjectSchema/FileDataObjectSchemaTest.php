<?php

namespace Heystack\Core\DataObjectSchema;

use Heystack\Core\Exception\ConfigurationException;

class FileDataObjectSchemaTest extends \PHPUnit_Framework_TestCase
{
    protected $stateStub;
    protected $schema;

    protected function setUp()
    {
        $this->stateStub = $this->getMockBuilder('Heystack\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        $this->stateStub = null;
    }

    public function testSchemaExceptions()
    {

        $this->assertEquals('Configuration Error: Your config is empty', $this->helperTryCatch(false));
        $this->assertEquals('Configuration Error: Identifier missing', $this->helperTryCatch([]));
        $this->assertEquals(
            'Configuration Error: Flat config missing',
            $this->helperTryCatch(
                [
                    'id' => 'test'
                ]
            )
        );
        $this->assertEquals(
            'Configuration Error: Related config missing',
            $this->helperTryCatch(
                [
                    'id'   => 'test',
                    'flat' => [
                        'Test' => 'Text'
                    ]
                ]
            )
        );
        $this->assertNull(
            $this->helperTryCatch(
                [
                    'id'      => 'test',
                    'flat'    => [
                        'Test' => 'Text'
                    ],
                    'related' => [
                        'Something'
                    ]
                ]
            )
        );

    }

    public function helperTryCatch($config)
    {
        try {

            $stub = $this->getMockBuilder(
                'Heystack\Core\DataObjectGenerate\FileDataObjectSchema'
            )->disableOriginalConstructor()->getMockForAbstractClass();

            $stub->expects($this->any())
                ->method('parseFile')
                ->will($this->returnValue($config));

            $stub->__construct(serialize($config), $this->stateStub);

        } catch (ConfigurationException $e) {
            return $e->getMessage();

        }

        return null;
    }
}
