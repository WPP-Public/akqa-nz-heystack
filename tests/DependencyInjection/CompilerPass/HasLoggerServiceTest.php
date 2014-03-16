<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

class HasLoggerServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\HasLoggerService::getServiceName
     */
    public function testServiceName()
    {
        $method = new \ReflectionMethod(
            __NAMESPACE__.'\HasLoggerService',
            'getServiceName'
        );

        $method->setAccessible(true);

        $this->assertEquals(
            'logger',
            $method->invoke(new HasLoggerService())
        );
    }
    /**
     * @covers \Heystack\Core\DependencyInjection\CompilerPass\HasLoggerService::getServiceSetterName
     */
    public function testServiceSetterName()
    {
        $method = new \ReflectionMethod(
            __NAMESPACE__.'\HasLoggerService',
            'getServiceSetterName'
        );

        $method->setAccessible(true);

        $this->assertEquals(
            'setLogger',
            $method->invoke(new HasLoggerService())
        );
    }
}