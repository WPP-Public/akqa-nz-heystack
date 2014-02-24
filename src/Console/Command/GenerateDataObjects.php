<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

namespace Heystack\Core\Console\Command;

use Heystack\Core\Generate\DataObjectGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

/**
 * @author  Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack
 */
class GenerateDataObjects extends Command
{

    /**
     * @var \Heystack\Core\Generate\DataObjectGenerator
     */
    private $generatorService;
    /**
     * @param \Heystack\Core\Generate\DataObjectGenerator $generatorService
     */
    public function __construct(DataObjectGenerator $generatorService)
    {
        $this->generatorService = $generatorService;
        parent::__construct();
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
                Input\InputOption::VALUE_NONE,
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
