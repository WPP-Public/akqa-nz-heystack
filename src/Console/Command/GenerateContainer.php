<?php

namespace Heystack\Core\Console\Command;

use Heystack\Core\DependencyInjection\SilverStripe\HeystackSilverStripeContainerBuilder;
use RuntimeException;
use Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;
use Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Dumper\YamlDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Generates the dependency injection container
 * 
 * This command generates the DI container from the provided services files
 * 
 * It is also used via the "GenerateContainerDataObjectTrait" to regenerate the container
 * on certain database writes
 * 
 * @package Heystack\Core\Console\Command
 */
class GenerateContainer extends Command
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    protected $heystackBasePath;

    /**
     * @param string|void $basePath
     * @param string|void $heystackBasePath
     */
    public function __construct($basePath = null, $heystackBasePath = null)
    {
        $this->basePath = $basePath ?: BASE_PATH;
        $this->heystackBasePath = $heystackBasePath ?: HEYSTACK_BASE_PATH;
        parent::__construct();
    }

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
            )
            ->addOption(
                'debug',
                'd',
                Input\InputOption::VALUE_NONE,
                'Debug the container output'
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
        // Get mode
        if ($input->getOption('mode')) {
            $mode = $input->getOption('mode');
        } else {
            $mode = \Director::get_environment_type();
        }

        $container = $this->createContainer();

        $this->loadConfig($container, $mode);
        
        $output->writeln(
            $this->dumpContainer($container, $mode, $input->getOption('debug'))
        );
    }

    /**
     * @return HeystackSilverStripeContainerBuilder
     */
    protected function createContainer()
    {
        $container = new HeystackSilverStripeContainerBuilder();

        if (class_exists('Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator')) {
            $container->setProxyInstantiator(new RuntimeInstantiator());
        }

        foreach (new \DirectoryIterator($this->basePath) as $directory) {
            $directory = $this->basePath . '/' . $directory;

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
            new FileLocator($this->basePath . '/mysite/config/')
        ))->load("services_$mode.yml");
    }

    /**
     * @param \Heystack\Core\DependencyInjection\SilverStripe\HeystackSilverStripeContainerBuilder $container
     * @param $mode
     * @param bool $debug
     * @return string
     */
    protected function dumpContainer(HeystackSilverStripeContainerBuilder $container, $mode, $debug = false)
    {
        $location = $this->heystackBasePath . '/cache/';

        if (!file_exists($location)) {
            mkdir($location, 0777, true);
        }

        $class = "HeystackServiceContainer$mode";

        $container->compile();
        
        if ($debug) {
            $dumper = new YamlDumper($container);
            return $dumper->dump();
        } else {
            $dumper = new PhpDumper($container);
            if (class_exists('Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper')) {
                $dumper->setProxyDumper(new ProxyDumper());
            }

            file_put_contents(
                $this->getRealPath($location) . "/$class.php",
                $dumper->dump([
                    'class' => $class,
                    'base_class' => "Heystack\\Core\\DependencyInjection\\SilverStripe\\HeystackSilverStripeContainer"
                ])
            );
            
            return 'Container generated';
        }
    }

    /**
     * Allows testing with vfsStream
     * @param $path
     * @return string
     */
    protected function getRealPath($path)
    {
        return realpath($path);
    }
}
