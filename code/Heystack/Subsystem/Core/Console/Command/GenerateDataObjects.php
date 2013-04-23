<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

namespace Heystack\Subsystem\Core\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;
use Heystack\Subsystem\Core\Generate\DataObjectGenerator;

/**
 * Kicks off the generator service from a cli controller
 *
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Heystack
 */
class GenerateDataObjects extends Command
{

    /**
     * @var \Heystack\Subsystem\Core\Generate\DataObjectGenerator
     */
    private $generatorService;
    /**
     * @param \Heystack\Subsystem\Core\Generate\DataObjectGenerator $generatorService
     */
    public function __construct(DOGenerator $generatorService)
    {
        $this->generatorService = $generatorService;
    }
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('generate-dataobjects')
            ->setDescription('Generate dataobjects')
            ->addOption(
                'force',
                'f',
                Input\InputOption::VALUE_OPTIONAL,
                'Whether to force the generation',
                null
            );
    }
    /**
     * Generate the container
     * @param  Input\InputInterface   $input  The commands input
     * @param  Output\OutputInterface $output The commands output
     * @throws RuntimeException
     * @return null
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        $this->generatorService->process($input->getOption('force'));
        $output->writeln('Completed');
    }
}
