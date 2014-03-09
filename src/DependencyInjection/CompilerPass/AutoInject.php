<?php

namespace Heystack\Core\DependencyInjection\CompilerPass;

use Heystack\Core\Exception\ConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class AutoInject
 * 
 * The purpose of this class is to find services that are requesting auto-injection
 * and configure their definitions to have "provided" services auto-injected
 * 
 * The relevant tags are:
 * 
 * "autoinject"
 * 
 * Example: { name: autoinject, arguments: true, setter: true, adder: true }
 * Example: { name: autoinject, all: true }
 * 
 * Both "setter", "arguments" and "adder" are optional
 * 
 * When "setter" is set to true, the compiler pass will attempt to find provided services that match
 * the argument of setters found on the class
 * 
 * When "arguments" is set to true the compiler pass will attempt to find services for the arguments
 * currently support for injection parameters is not supported, only services
 *
 * When "adder" is set to true the compiler pass will attempt to find services that match the
 * argument of adder methods found of the class, and it will add a method call to the adder
 * for each service found
 * 
 * When "all" is set to true, the above settings all apply
 * 
 * "autoinject.provides"
 * 
 * Example: { name: autoinject.provides, interfaces: true, classes: true }
 * Example: { name: autoinject.provides, all: true }
 * 
 * Both "interfaces" and "class" are optional
 * 
 * When "interfaces" is set to true, the compiler pass will register the service as providing an instance of
 * all interfaces that the class implemented, so when interfaces are encountered in arguments, setters and adders;
 * the provided service will be supplied
 * 
 * When "classes" is set to true, the compiler pass will register the service as providing an instance of
 * that class and parent classes, so when a class of the same type is encountered in a arguments, setter or add; the provided
 * service will be supplied
 * 
 * When "all" is set to true, the above settings all apply
 * 
 * @author Cam Spiers
 * @package Heystack\Core\DependencyInjection\CompilerPass
 */
final class AutoInject implements CompilerPassInterface
{
    /**
     * The auto inject tag used to indicate that a service should be auto-injected
     */
    const TAG_AUTO_INJECT = 'autoinject';
    /**
     * The provides tag used to indicate that a service should provide
     */
    const TAG_AUTO_INJECT_PROVIDES = 'autoinject.provides';
    /**
     * A config option used in both TAG_AUTO_INJECT and TAG_AUTO_INJECT_PROVIDES used
     * to allow all config options for that tag 
     */
    const CONFIG_ALL = 'all';
    /**
     * A config option used in TAG_AUTO_INJECT to indicate that argument auto injection should be performed
     */
    const CONFIG_ARGUMENTS = 'arguments';
    /**
     * A config option used in TAG_AUTO_INJECT to indicate that setter injection should be performed
     */
    const CONFIG_SETTER = 'setter';
    /**
     * A config option used in TAG_AUTO_INJECT to indicate that adder injection should be performed
     */
    const CONFIG_ADDER = 'adder';
    /**
     * A config option used in TAG_AUTO_INJECT_PROVIDES to indicate that interfaces of the service
     * should be considered provided for 
     */
    const CONFIG_INTERFACES = 'interfaces';
    /**
     * A config option used in TAG_AUTO_INJECT_PROVIDES to indicate that the class and parent classes of the service
     * should be considered provided for
     */
    const CONFIG_CLASSES = 'classes';
    /**
     * Include methods explicitly. Methods not in this list will be excluded
     */
    const CONFIG_INCLUDE = 'include';
    /**
     * Exclude methods explicitly. Methods in this list will be excluded
     */
    const CONFIG_EXCLUDE = 'exclude';
    /**
     * Regex for setter methods
     */
    const REGEX_SETTER = '/^set[a-zA-Z0-9_\x7f-\xff]+/';
    /**
     * Regex for adder methods
     */
    const REGEX_ADDER = '/^add[a-zA-Z0-9_\x7f-\xff]*/';

    /**
     * Store a interface name indexed array of multiple references
     * @var array
     */
    private $interfaceProviders = [];

    /**
     * Store a class name indexed array of multiple references
     * @var array
     */
    private $classProviders = [];

    /**
     * @param ContainerBuilder $container
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
    public function process(ContainerBuilder $container)
    {   
        // Build up lists of interfaces and classes that are provided for
        foreach ($container->findTaggedServiceIds(self::TAG_AUTO_INJECT_PROVIDES) as $id => $attrs) {
            $reflectionClass = $this->getReflectionClass($container, $container->getDefinition($id));
            $reference = new Reference($id);
            
            foreach ($attrs as $attr) {
                if ($this->hasConfig(self::CONFIG_INTERFACES, $attr)) {
                    foreach ($reflectionClass->getInterfaceNames() as $interface) {
                        $this->addInterfaceProvider($interface, $reference);
                    }
                }

                if ($this->hasConfig(self::CONFIG_CLASSES, $attr)) {
                    $this->addClassProvider($reflectionClass->getName(), $reference);
                    // Register the service as provided for the parent classes too
                    while ($parentReflectionClass = $reflectionClass->getParentClass()) {
                        $this->addClassProvider($parentReflectionClass->getName(), $reference);
                        $reflectionClass = $parentReflectionClass;
                    }
                }
            }
        }
        
        // Find services that are requesting auto-injection
        foreach ($container->findTaggedServiceIds(self::TAG_AUTO_INJECT) as $id => $attrs) {
            $definition = $container->getDefinition($id);
            $reflectionClass = $this->getReflectionClass($container, $definition);
            
            // Keep track of the services provided, so we don't later use setter injection when not needed
            $constructorProvided = [];
            
            foreach ($attrs as $attr) {
                if ($this->hasConfig(self::CONFIG_ARGUMENTS, $attr)) {
                    // arguments injection
                    $arguments = $definition->getArguments();
                    
                    foreach ($reflectionClass->getConstructor()->getParameters() as $index => $reflectionParameter) {
                        // We already have this argument provided, so skip
                        if (isset($arguments[$index])) {
                            continue;
                        }
                        
                        // If the argument is optional, then just default in its default value
                        if ($reflectionParameter->isOptional()) {
                            $arguments[$index] = $reflectionParameter->getDefaultValue();
                            continue;
                        }

                        $reflectionParameterClass = $reflectionParameter->getClass();

                        // If a scalar value isn't provider, and there is no default value
                        // we need to inform the use that we can't provide scalar value auto-injection
                        if (!$reflectionParameterClass instanceof ReflectionClass) {
                            throw new ConfigurationException(
                                sprintf(
                                    "Failed to inject scalar argument '%s' for service '%s'. " .
                                    "To use scalar parameters with AutoInject, specify the " .
                                    "arguments 0-based position number explicitly in the configuration",
                                    $reflectionParameter->getName(),
                                    $id
                                )
                            );
                        }

                        $reflectionParameterClassName = $reflectionParameterClass->getName();
                        $provider = $this->getProvider($reflectionParameterClassName);

                        // If we can't find a provided service then tell the user
                        if (!$provider) {
                            throw new ConfigurationException(
                                sprintf(
                                    "A service providing for the %s '%s' was not found when " .
                                    "auto-injecting the service '%s'",
                                    $reflectionParameterClass->isInterface() ? 'interface' : 'class',
                                    $reflectionParameterClassName,
                                    $id
                                )
                            );
                        }

                        $arguments[$index] = $provider;
                        $constructorProvided[] = $provider;
                    }
                    
                    // Ensure that the arguments are ordered by their index
                    ksort($arguments);
                    $definition->setArguments($arguments);
                }
                
                if ($this->hasConfig(self::CONFIG_SETTER, $attr)) {
                    $setterMethods = $this->getInjectableMethods($reflectionClass, self::REGEX_SETTER, $attr);
                    
                    foreach ($setterMethods as $reflectionMethod) {
                        $reflectionMethodName = $reflectionMethod->getName();
                        
                        $provider = $this->getProvider(
                            $reflectionMethod->getParameters()[0]
                                ->getClass()
                                    ->getName()
                        );

                        if (!$provider || in_array($provider, $constructorProvided)) {
                            continue;
                        }

                        $definition->addMethodCall($reflectionMethodName, [$provider]);
                    }
                }

                if ($this->hasConfig(self::CONFIG_ADDER, $attr)) {
                    $adderMethods = $this->getInjectableMethods($reflectionClass, self::REGEX_ADDER, $attr);

                    foreach ($adderMethods as $reflectionMethod) {
                        $reflectionMethodName = $reflectionMethod->getName();
                        
                        $providers = $this->getProviders(
                            $reflectionMethod->getParameters()[0]
                                ->getClass()
                                    ->getName()
                        );
                        
                        foreach ($providers as $provider) {
                            $definition->addMethodCall($reflectionMethodName, [$provider]);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param Definition $definition
     * @return \ReflectionClass
     */
    private function getReflectionClass(ContainerBuilder $container, Definition $definition)
    {
        return new \ReflectionClass(
            $container->getParameterBag()->resolveValue(
                $definition->getClass()
            )
        );
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param $regex
     * @param $attr
     * @return \ReflectionMethod[]
     */
    private function getInjectableMethods(ReflectionClass $reflectionClass, $regex, $attr)
    {
        $methods = [];
        
        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
            if ($reflectionMethod->isStatic()) {
                continue;
            }

            $reflectionMethodName = $reflectionMethod->getName();

            if (!preg_match($regex, $reflectionMethodName)) {
                continue;
            }

            if (!$this->isMethodIncluded($reflectionMethodName, $attr) || $this->isMethodExcluded($reflectionMethodName, $attr)) {
                continue;
            }

            $reflectionParameters = $reflectionMethod->getParameters();

            // Methods should only take one parameter
            if (count($reflectionParameters) !== 1) {
                continue;
            }

            $reflectionParameterClass = $reflectionParameters[0]->getClass();

            if (!$reflectionParameterClass instanceof ReflectionClass) {
                continue;
            }

            $methods[] = $reflectionMethod;
        }
        
        return $methods;
    }

    /**
     * Add an interface provider to the list
     * @param string $interface
     * @param Reference $reference
     */
    private function addInterfaceProvider($interface, Reference $reference)
    {
        if (empty($this->interfaceProviders[$interface])) {
            $this->interfaceProviders[$interface]= [];
        }

        $this->interfaceProviders[$interface][] = $reference;
    }

    /**
     * Add a class provider to the list
     * @param string $class
     * @param Reference $reference
     */
    private function addClassProvider($class, Reference $reference)
    {
        if (empty($this->classProviders[$class])) {
            $this->classProviders[$class] = [];
        }
        
        $this->classProviders[$class][] = $reference;
    }

    /**
     * Get a provider for an identifier
     * using this method when there are multiple providers for an
     * identifier will cause an exception to be thrown
     * @param $identifier
     * @return mixed
     * @throws \InvalidArgumentException
     */
    private function getProvider($identifier)
    {
        if (isset($this->interfaceProviders[$identifier])) {
            $this->assertSingleInterfaceProvider($identifier);
            return reset($this->interfaceProviders[$identifier]);
        } elseif (isset($this->classProviders[$identifier])) {
            $this->assertSingleClassProvider($identifier);
            return reset($this->classProviders[$identifier]);
        } else {
            return false;
        }
    }

    /**
     * Get all providers for a specified identifier
     * @param $identifier
     * @return Reference[]
     */
    private function getProviders($identifier)
    {
        if (isset($this->interfaceProviders[$identifier])) {
            return $this->interfaceProviders[$identifier];
        } elseif (isset($this->classProviders[$identifier])) {
            return $this->classProviders[$identifier];
        } else {
            return [];
        }
    }

    /**
     * Throws an exception if there is more than one service provided
     * This is used when doing setter and constructor injection
     * @param string $identifier
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
    private function assertSingleInterfaceProvider($identifier)
    {
        if (count($this->interfaceProviders[$identifier]) > 1) {
            throw new ConfigurationException(
                sprintf(
                    "Expected only 1 interface provider for '%s' but got %d:\n'%s' " .
                    "because of this the service can not be uniquely resolved",
                    $identifier,
                    count($this->interfaceProviders[$identifier]),
                    implode(', ', $this->interfaceProviders[$identifier])
                )
            );
        }
    }

    /**
     * Throws an exception if there is more than one service provided
     * This is used when doing setter and constructor injection
     * @param string $identifier
     * @throws \Heystack\Core\Exception\ConfigurationException
     */
    private function assertSingleClassProvider($identifier)
    {
        if (count($this->classProviders[$identifier]) > 1) {
            throw new ConfigurationException(
                sprintf(
                    "Expected only 1 class provider for '%s' but got %d:\n'%s' " .
                    "because of this the service can not be uniquely resolved",
                    $identifier,
                    count($this->classProviders[$identifier]),
                    implode(', ', $this->classProviders[$identifier])
                )
            );
        }
    }

    /**
     * Checks if a method is in the include list
     * @param $methodName
     * @param $attr
     * @return bool
     */
    private function isMethodIncluded($methodName, $attr)
    {
        if (empty($attr[self::CONFIG_INCLUDE]) || !is_array($attr[self::CONFIG_INCLUDE])) {
            return true;
        }
        
        return in_array($methodName, $attr[self::CONFIG_INCLUDE]);
    }

    /**
     * Checks if a method is in the exclude list
     * @param $methodName
     * @param $attr
     * @return bool
     */
    private function isMethodExcluded($methodName, $attr)
    {
        if (empty($attr[self::CONFIG_EXCLUDE]) || !is_array($attr[self::CONFIG_EXCLUDE])) {
            return false;
        }

        return in_array($methodName, $attr[self::CONFIG_EXCLUDE]);
    }

    /**
     * Tests whether ot not a config option has been provided in the tag
     * If the "all" setting is present, this will return true
     * @param string $config
     * @param array $attr
     * @return bool
     */
    private function hasConfig($config, $attr)
    {
        return (isset($attr[self::CONFIG_ALL]) && $attr[self::CONFIG_ALL]) || (isset($attr[$config]) && $attr[$config]);
    }
} 