<?php

namespace Heystack\Subsystem\Core\Console\Command;

use Camspiers\DependencyInjection\SharedContainerFactory;
use Heystack\Subsystem\Core\Console\Application;
use Heystack\Subsystem\Core\ServiceStore;
use Heystack\Subsystem\Core\Services;
use Monolog\Logger;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
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

        try {

            SharedContainerFactory::dumpContainer(
                SharedContainerFactory::createContainer(
                    array(
                        BASE_PATH . '/mysite/config/',
                        HEYSTACK_BASE_PATH . '/config/'
                    ),
                    "services_$mode.yml"
                ),
                "HeystackServiceContainer$mode",
                HEYSTACK_BASE_PATH . '/cache/'
            );

            $output->writeln('Container generated');

        } catch (\Exception $e) {

            $logger = $this->getLogger();
            if ($logger instanceof Logger) {
                $logger->addCritical($e->getMessage());
            } else {
                throw new \Exception($e->getMessage());
            }

        }
    }

    protected function getLogger()
    {
        $application = $this->getApplication();
        if ($application instanceof Application) {
            return $application->getLogger();
        } else {
            return ServiceStore::getService(Services::MONOLOG);
        }
    }
}
