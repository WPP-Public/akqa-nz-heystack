<?php

namespace Heystack\Subsystem\Core\Console\Command;

use Camspiers\DependencyInjection\SharedContainerFactory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
use RuntimeException;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class GenerateContainer extends Command
{
    /**
     * Configure the commands options
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('generate-container')
            ->setDescription('Generate container')
            ->addOption(
                'mode',
                'm',
                Input\InputOption::VALUE_OPTIONAL,
                'The mode (dev, live, test)',
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
        $mode = \Director::get_environment_type();

        if ($input->getOption('mode')) {
            $mode = $input->getOption('mode');
        }

        SharedContainerFactory::requireExtensionConfigs(
            array(
                BASE_PATH . '/*/config/extensions.php'
            )
        );

        SharedContainerFactory::dumpContainer(
            SharedContainerFactory::createContainer(
                array(
                    BASE_PATH . '/mysite/config/',
                    HEYSTACK_BASE_PATH . '/config/'
                ),
                "services_$mode.yml"
            ),
            'HeystackServiceContainer',
            BASE_PATH . '/mysite/code/'
        );

        $output->writeln('Container generated');
    }
}