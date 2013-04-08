<?php

namespace Heystack\Subsystem\Core\Console\Command;

use Camspiers\DependencyInjection\SharedContainerFactory;
use Symfony\Component\Console\Command\Command;
use RuntimeException;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;

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
            ->setDescription('Generate container');
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
        $servicesConfigruationPath = HEYSTACK_BASE_PATH . '/config';
        if (file_exists(BASE_PATH . '/mysite/config/services.yml')) {
            $servicesConfigruationPath = BASE_PATH . '/mysite/config';
        }
        SharedContainerFactory::requireExtensionConfigs(
            array(
                BASE_PATH . '/*/config/extensions.php'
            )
        );
        SharedContainerFactory::dumpContainer(
            $container = SharedContainerFactory::createContainer(
                array(),
                $servicesConfigruationPath . '/services.yml'
            ),
            'HeystackServiceContainer',
            BASE_PATH . '/mysite/code/'
        );
        $output->writeln('Container generated');
    }
}