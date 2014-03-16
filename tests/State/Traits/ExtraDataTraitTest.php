<?php

namespace Heystack\Core\State\Traits;

class ExtraDataTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\State\Traits\ExtraDataTrait::setExtraData
     */
    public function testSetExtraDataWorks()
    {
        $t = $this->getObjectForTrait(__NAMESPACE__.'\ExtraDataTrait');
        $t->setExtraData(['a' => true]);
        $this->assertAttributeEquals(true, 'a', $t);
    }
}

