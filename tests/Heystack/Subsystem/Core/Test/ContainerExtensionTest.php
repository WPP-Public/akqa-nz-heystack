<?php

namespace Heystack\Subsystem\Core\Test;

use Heystack\Subsystem\Core\DependencyInjection\ContainerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerExtension
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ContainerExtension;
    }

    protected function tearDown()
    {
        $this->object = null;
    }

    public function testSetGetFolder()
    {
        $this->object->setFolder('hello');
        $this->assertEquals('hello', $this->object->getFolder());
    }

    public function testLoad()
    {

        $containerBuilder = new ContainerBuilder();

        $this->object->setFolder('/tests/Heystack/Subsystem/Core/Test/services/');

        $this->object->load(array(), $containerBuilder);

        $this->assertTrue($containerBuilder->has('example'));

        $this->assertEquals('Test', $containerBuilder->getDefinition('example')->getClass());

    }

    public function testGetNamespace()
    {
        $this->assertEquals(ContainerExtension::IDENTIFIER, $this->object->getNamespace());
    }

    public function testGetXsdValidationBasePath()
    {
        $this->assertEquals(false, $this->object->getXsdValidationBasePath());
    }

    public function testGetAlias()
    {
        $this->assertEquals(ContainerExtension::IDENTIFIER, $this->object->getAlias());
    }
}
