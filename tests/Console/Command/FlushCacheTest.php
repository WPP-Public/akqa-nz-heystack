<?php

namespace Heystack\Core\Console\Command;

use Heystack\Core\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class FlushCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Heystack\Core\Console\Command\FlushCache::execute
     * @covers \Heystack\Core\Console\Command\FlushCache::configure
     */
    public function testCacheDidFlush()
    {
        $cache = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods(['flushAll'])
            ->getMockForAbstractClass();

        $cache->expects($this->once())
            ->method('flushAll');

        $command = new FlushCache();
        $command->setCacheService($cache);

        $application = new Application();
        $application->add($command);
        $application->find('flush-cache');
        
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);
    }

    /**
     * @covers \Heystack\Core\Console\Command\FlushCache::execute
     * @covers \Heystack\Core\Console\Command\FlushCache::configure
     */
    public function testCacheDidntFlush()
    {
        $cache = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->setMethods(['flushAll'])
            ->getMockForAbstractClass();

        $cache->expects($this->never())
            ->method('flushAll');

        $command = new FlushCache();

        $application = new Application();
        $application->add($command);
        $application->find('flush-cache');

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);
    }
}