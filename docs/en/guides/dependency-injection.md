# Dependency Injection System

DI is a good pattern for unit testable, customisable and decoupled code. Heystack provides a central container that extending modules can add to or alter and enhance the behaviour of an application.

## How it works

A Symfony DI container is a class that creates and handles the sharing of objects. To do this the container needs to know what services it can constuct and how it should construct them.

### Configuring the container

To know how to contruct services the container needs to be configured. To do this heystack and heystack modules optionally use three tools:

1. A `config/extensions.php` file defined by each SilverStripe module (including heystack)
2. A implementor of Symfony DI's ExtensionInterface
3. A implementor of Symfony DI's CompilerPassInterface

The purpose of the `config/extensions.php` file is add your extensions and compiler passes to the `SharedContainerFactory`. Heystack itself provides a container extension and various compiler passes.

The `SharedContainerFactory` produces a container with all extensions and compiler passes added. It does this by loading a root `yml` file from `mysite/config`.

### The mysite `yml` files

It is the responsiblility or users of heystack to define three `yml` files:

* `mysite/config/services_dev.yml`
* `mysite/config/services_test.yml`
* `mysite/config/services_live.yml`

The purpose of the three files is to allow different containers to be constructed depending on the environment type.

A common setup is to have each environment file include a common file:

* `mysite/config/services_dev.yml`
* `mysite/config/services_test.yml`
* `mysite/config/services_live.yml`

```
imports:
    - { resource: services.yml }
```

`mysite/config/services.yml`:

```
heystack: # this will load the "heystack" container (this is required)
specialmodule: # this will load the "specialmodule" container
services: # here services can be defined
parameters: # here parameters can be defined
```

### Container extensions

Each module using heystack can optionally provide container extensions. Container extensions are classes that implement the `Symfony\Component\DependencyInjection\Extension\ExtensionInterface` interface.

Container extensions allow your module to alter the container by loading a `yml` or `xml` file which defines services and parameters. See [Managing Configuration With Extensions](http://symfony.com/doc/current/components/dependency_injection/compilation.html#managing-configuration-with-extensions)

To let heystack know about your container extension, you need to register the extension with the `SharedContainerFactory`:

`config/extensions.php`:

```
use Camspiers\DependencyInjection\SharedContainerFactory;
SharedContainerFactory::addExtension(new MyContainerExtension());
```

When you have added a new container extension, you need to ensure it is loaded from your mysite services file:

`mysite/config/services`

```
...
myextension: # this will load the "myextension" container
...
```

### Compiler passes

Each module using heystack can optionally provide compiler passes. Compiler passes are classes that implements the `Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface` interface.

Compiler passes are a mechanism to alter the way the container is built after services from all container extensions are defined. A common example of the use of compiler passes is service tagging. See [Creating a Compiler Pass](http://symfony.com/doc/current/components/dependency_injection/compilation.html#creating-a-compiler-pass) and [Working with Tagged Services](http://symfony.com/doc/current/components/dependency_injection/tags.html) for more information.

To let heystack know about your compiler pass, you need to register the compiler pass with the `SharedContainerFactory`:

`config/extensions.php`:

```
use Camspiers\DependencyInjection\SharedContainerFactory;
SharedContainerFactory::addCompilerPass(new MyCompilerPass());
```

#### Service tagging

Compiler passes allow the tagged services. Adding a tag to a service will alter the container in the way the compiler pass specifies.

A simple example is a compiler pass that looks for services tagged as `command` and adds a method call to the application service in result:

```
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
class MyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('application')) {
            foreach ($container->findTaggedServiceIds('command') as $id => $attributes) {
                $container->getDefinition('application')->addMethodCall('addCommand', [new Reference($id)])
            }
        }
    }
}
```

This example will find all services tagged with `command` and add a `addCommand` method call to the `application` service.

Given the following config:

```
application:
	class: MyApplication
mycommand:
	class: MyCommand
	tags:
		- { name: command }
```		

Something similar to the follow will result (ignoring caching):

```
public function getApplication()
{
    $service = new MyApplication();
    $service->addCommand($this->getMycommand());
    return $service;
}
public function getMycommand
{
    return new MyCommand();
}
```

### Heystack service tags

* `console.application.command`
	* Adds the tagged service to the console application
* `dataobject_generator.schema`
	* Adds the tagged service to the DataObjectGenerator 
* `event_dispatcher.subscriber`
	* Adds the tagged service as a subscriber on the event dispatcher
* `input_processor_handler.processor`
	* Adds the tagged service as a processor on the input processor 
* `output_processor_handler.processor`
	* Adds the tagged service as a processor on the output processor
* `ss_orm_backend.data_provider`
	* Adds the tagged service as a data provider to the SilverStripe ORM backend
