<?php

namespace Heystack\Core\DataObjectSchema;

use Doctrine\Common\Cache\ArrayCache;
use org\bovigo\vfs\vfsStream;

/**
 * Class YamlDataObjectSchemaTest
 * @package Heystack\Core\DataObjectSchema
 */
class YamlDataObjectSchemaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    protected $schema;

    /**
     *
     */
    protected function setUp()
    {   
        vfsStream::setup('root', null, [
            'schema.yml' => <<<YAML
id: Test
flat:
  Test: Text
parent:
children:
  Tests: +Test
YAML
        ]);


        $this->schema = new YamlDataObjectSchema(
            vfsStream::url('root/schema.yml'),
            new ArrayCache()
        );
    }

    /**
     * @covers \Heystack\Core\DataObjectSchema\YamlDataObjectSchema::parseFile
     */
    public function testSchemaConstruct()
    {
        $this->assertTrue(
            is_object($this->schema)
        );
    }

    /**
     * @expectedException \Heystack\Core\Exception\ConfigurationException
     * @covers \Heystack\Core\DataObjectSchema\YamlDataObjectSchema::parseFile
     */
    public function testNoConstruct()
    {
        new YamlDataObjectSchema(
            vfsStream::url('root/fake.yml'),
            new ArrayCache()
        );
    }
}
