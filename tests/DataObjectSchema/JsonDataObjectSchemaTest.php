<?php

namespace Heystack\Core\DataObjectSchema;

use Doctrine\Common\Cache\ArrayCache;
use org\bovigo\vfs\vfsStream;

/**
 * Class JsonDataObjectSchemaTest
 * @package Heystack\Core\DataObjectSchema
 */
class JsonDataObjectSchemaTest extends \PHPUnit_Framework_TestCase
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
            'schema.json' => <<<JSON
{
   "id": "Test",
   "flat":{
      "Test":"Text"
   },
   "parent":null,
   "children":{
      "Tests":"+Test"
   }
}
JSON
        ]);


        $this->schema = new JsonDataObjectSchema(
            vfsStream::url('root/schema.json'),
            new ArrayCache()
        );
    }

    /**
     * @covers \Heystack\Core\DataObjectSchema\JsonDataObjectSchema::parseFile
     */
    public function testSchemaConstruct()
    {
        $this->assertTrue(
            is_object($this->schema)
        );
    }

    /**
     * @expectedException \Heystack\Core\Exception\ConfigurationException
     * @covers \Heystack\Core\DataObjectSchema\JsonDataObjectSchema::parseFile
     */
    public function testNoConstruct()
    {
        new JsonDataObjectSchema(
            vfsStream::url('root/fake.json'),
            new ArrayCache()
        );
    }
}
