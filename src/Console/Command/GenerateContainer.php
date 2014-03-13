<?php

namespace Heystack\Core\Console\Command;

use Heystack\Core\DependencyInjection\SilverStripe\HeystackSilverStripeContainerBuilder;
use RuntimeException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Generates the dependency injection container
 * 
 * This command generates teh DI container from the provided services files
 * 
 * It is also used via the "GenerateContainerDataObjectTrait" to regenerate the container
 * on certain database writes
 * 
 * @package Heystack\Core\Console\Command
 */
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
     * @param  Input\InputInterface   $input
     * @param  Output\OutputInterface $output
     * @return void
     * @throws \Exception
     */
    protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
    {
        // Ensure database connection
        global $databaseConfig;
        \DB::connect($databaseConfig);

        // Get mode
        if ($input->getOption('mode')) {
            $mode = $input->getOption('mode');
        } else {
            $mode = \Director::get_environment_type();
        }

        $container = $this->createContainer();

        $this->loadConfig($container, $mode);

        $this->dumpContainer($container, $mode);

        $output->writeln('Container generated');
    }

    /**
     * @return HeystackSilverStripeContainerBuilder
     */
    protected function createContainer()
    {
        $container = new HeystackSilverStripeContainerBuilder();

        foreach (new \DirectoryIterator(BASE_PATH) as $directory) {
            $directory = BASE_PATH . '/' . $directory;

            if (!file_exists($directory . '/_heystack_subsystem')) {
                continue;
            }

            if (file_exists($directory . '/config/extensions.php')) {
                $extensions = require $directory . '/config/extensions.php';

                if (is_array($extensions)) {
                    foreach ($extensions as $extension) {
                        $container->registerExtension($extension);
                    }
                }
            }

            if (file_exists($directory . '/config/compiler_passes.php')) {
                $compilerPasses = require $directory . '/config/compiler_passes.php';

                if (is_array($compilerPasses)) {
                    foreach ($compilerPasses as $compilerPass) {
                        if (is_array($compilerPass)) {
                            list($compilerPass, $type) = $compilerPass;
                        } else {
                            $type = PassConfig::TYPE_BEFORE_OPTIMIZATION;
                        }
                        $container->addCompilerPass($compilerPass, $type);
                    }
                }
            }
        }

        return $container;
    }

    /**
     * @param $container
     * @param $mode
     */
    protected function loadConfig($container, $mode)
    {
        (new YamlFileLoader(
            $container,
            new FileLocator(BASE_PATH . '/mysite/config/')
        ))->load("services_$mode.yml");
    }

    /**
     * @param $container
     * @param $mode
     * @throws \RuntimeException
     */
    protected function dumpContainer(HeystackSilverStripeContainerBuilder $container, $mode)
    {
        $location = HEYSTACK_BASE_PATH . '/cache/';

        if (!file_exists($location)) {
            throw new RuntimeException('Dump location does not exist');
        }

        $class = "HeystackServiceContainer$mode";

        $container->compile();

        $dumper = new PhpDumper($container);

        file_put_contents(
            realpath($location) . "/$class.php",
            $dumper->dump([
                'class' => $class,
                'base_class' => "Heystack\\Core\\DependencyInjection\\SilverStripe\\HeystackSilverStripeContainer"
            ])
        );
    }
}
