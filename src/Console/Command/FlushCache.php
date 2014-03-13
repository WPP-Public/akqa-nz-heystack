<?php

namespace Heystack\Core\Console\Command;

use Heystack\Core\Traits\HasCacheServiceTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

/**
 * Flushes the doctrine cache
 * 
 * This command is available via the heystack application for flushing all chache keys
 * 
 * @package Heystack\Core\Console\Command
 */
class FlushCache extends Command
{
    use HasCacheServiceTrait;

    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('flush-cache')
            ->setDescription('Flush the cache');
    }

    /**
     * @param  Input\InputInterface   $input
     * @param  Output\OutputInterface $output
     * @return void
     * @throws \Exception
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        if ($this->cacheService) {
            $this->cacheService->flushAll();
            $output->writeln('Cache flushed');
        } else {
            $output->writeln('No cache service provided');
        }
    }
} 