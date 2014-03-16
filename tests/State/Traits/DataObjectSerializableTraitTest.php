<?php

namespace Heystack\Core\State\Traits;

use Heystack\Core\State\ExtraDataInterface;

/**
 * @package Heystack\Core\State\Traits
 */
class DataObjectSerializableTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\State\Traits\DataObjectSerializableTrait::serialize
     */
    public function testSerializeWithoutExtraData()
    {
        $t = new TestDataObjectSerializableTrait();
        $this->assertEquals(
            serialize(['test' => true]),
            $data = $t->serialize()
        );
        
        return $data;
    }

    /**
     * @covers \Heystack\Core\State\Traits\DataObjectSerializableTrait::serialize
     */
    public function testSerializeWithExtraData()
    {
        $t = $this->getMockForAbstractClass(
            __NAMESPACE__ . '\TestDataObjectSerializableTraitWithExtraData'
        );
        
        $this->assertEquals(
            serialize([['test' => true], ['test' => true]]),
            $data = $t->serialize()
        );
        
        return $data;
    }

    /**
     * @depends testSerializeWithoutExtraData
     * @covers \Heystack\Core\State\Traits\DataObjectSerializableTrait::unserialize
     */
    public function testUnserializeWithoutExtraData($data)
    {
        $t = new TestDataObjectSerializableTrait();
        $t->unserialize($data);
        $this->assertAttributeEquals(
            __NAMESPACE__ . '\TestDataObjectSerializableTrait',
            'class',
            $t
        );

        $this->assertAttributeEquals(
            ['test' => true],
            'record',
            $t
        );

        $this->assertAttributeEquals(
            \DataModel::inst(),
            'model',
            $t
        );

    }

    /**
     * @depends testSerializeWithExtraData
     * @covers \Heystack\Core\State\Traits\DataObjectSerializableTrait::unserialize
     */
    public function testUnserializeWithExtraData($data)
    {
        $t = $this->getMockForAbstractClass(
            __NAMESPACE__ . '\TestDataObjectSerializableTraitWithExtraData'
        );
        
        $t->expects($this->once())
            ->method('setExtraData')
            ->with(['test' => true]);
        
        $t->unserialize($data);

        $this->assertAttributeEquals(
            get_class($t),
            'class',
            $t
        );

        $this->assertAttributeEquals(
            ['test' => true],
            'record',
            $t
        );

        $this->assertAttributeEquals(
            \DataModel::inst(),
            'model',
            $t
        );

    }
}

class TestDataObjectSerializableTrait
{
    use DataObjectSerializableTrait;

    /**
     * An method to ensure that toMap is implemented
     * @return array
     */
    public function toMap()
    {
        return ['test' => true];
    }
}

abstract class TestDataObjectSerializableTraitWithExtraData
    extends TestDataObjectSerializableTrait
    implements ExtraDataInterface
{
    /**
     * @return mixed
     */
    public function getExtraData()
    {
        return ['test' => true];
    }
}