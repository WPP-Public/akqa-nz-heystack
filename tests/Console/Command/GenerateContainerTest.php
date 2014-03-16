<?php

namespace Heystack\Core\Console\Command;

use Heystack\Core\Console\Application;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $rootFileSystem;

    public function setUp()
    {
        $this->rootFileSystem = vfsStream::setup();
        vfsStream::create([
            'testmodule' => [
                '_heystack_subsystem' => '',
                'config' => [
                    'extensions.php' => file_get_contents(HEYSTACK_BASE_PATH.'/config/extensions.php'),
                    'compiler_passes.php' => file_get_contents(HEYSTACK_BASE_PATH.'/config/compiler_passes.php')
                ]
            ],
            'mysite' => [
                'config' => [
                    'services_live.yml' => ""
                ]
            ],
            'heystack' => [
            ]
        ]);
    }

    /**
     * @covers \Heystack\Core\Console\Command\GenerateContainer::__construct
     * @covers \Heystack\Core\Console\Command\GenerateContainer::execute
     * @covers \Heystack\Core\Console\Command\GenerateContainer::configure
     * @covers \Heystack\Core\Console\Command\GenerateContainer::createContainer
     * @covers \Heystack\Core\Console\Command\GenerateContainer::loadConfig
     * @covers \Heystack\Core\Console\Command\GenerateContainer::dumpContainer
     */
    public function testContainerCreated()
    {
        $command = $this->getMock(
            __NAMESPACE__.'\GenerateContainer',
            [ 'getRealPath' ],
            [
                vfsStream::url('root'),
                vfsStream::url('root/heystack')
            ]
        );
        
        $command->expects($this->once())
            ->method('getRealPath')
            ->will($this->returnArgument(0));

        $application = new Application();
        $application->add($command);
        $application->find('generate-container');

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);
        
        $this->assertTrue(
            $this->rootFileSystem->hasChild('heystack/cache/HeystackServiceContainerlive.php')
        );

        $this->assertContains(
            'class HeystackServiceContainerlive extends Heystack\Core\DependencyInjection\SilverStripe\HeystackSilverStripeContainer',
            file_get_contents(vfsStream::url('root/heystack/cache/HeystackServiceContainerlive.php'))
        );
    }
}