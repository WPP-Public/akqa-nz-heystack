<?php

namespace Heystack\Core\Storage\Backends\SilverStripeOrm;

use Heystack\Core\State\State;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Heystack\Core\DataObjectGenerate\DataObjectGenerator;

use Heystack\Core\Exception\ConfigurationException;

use Heystack\Core\Storage\Backends\SilverStripeOrm\Backend;

//class BackendTest extends \PHPUnit_Framework_TestCase
//{
//
//    protected function setUp()
//    {
//        $this->state = $this->getMockBuilder('Heystack\Core\State\State')
//            ->disableOriginalConstructor()
//            ->getMock();
//        $this->backend = new Backend(
//            new EventDispatcher(),
//            new DataObjectGenerator(
//                $this->state
//            )
//        );
//    }
//
//    protected function tearDown()
//    {
//        $this->backend = null;
//    }
//
//    public function testIdentifier()
//    {
//
//        $this->assertEquals(Backend::IDENTIFIER, $this->backend->getIdentifier()->getFull());
//
//    }
//
//    public function testAddHasDataProvider()
//    {
//
//        $this->backend->addReferenceDataProvider(new TestStorable);
//
//        $this->assertTrue($this->backend->hasReferenceDataProvider('test'));
//
//    }
//
//    public function testWrite()
//    {
//
//        $object = new TestDataObjectStorable([
//            'Test' => 'Test'
//        ]);
//
//        $this->assertEquals('Configuration Error: Couldn\'t find data provider for identifier: ' . $object->getStorableIdentifier(), $this->tryCatchWriteHelper($object));
//
//        $this->backend->addDataProvider($object);
//
//        $this->assertEquals('Configuration Error: No schema found for identifier: ' . strtolower($object->getSchemaName()), $this->tryCatchWriteHelper($object));
//
//    }
//
//    protected function tryCatchWriteHelper($object)
//    {
//
//        try {
//
//            $this->backend->write($object);
//
//        } catch (ConfigurationException $e) {
//            return $e->getMessage();
//
//        }
//
//        return null;
//
//    }
//
//}
