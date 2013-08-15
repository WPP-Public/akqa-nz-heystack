# Heystack

Heystack is a SilverStripe module, designed to provide a set of programming tools that enable the creation of highly customisable applications.

# Tools provided

1. Dependency injection (DI) and configuration via Symfony's DI and configuration components
2. A state system for the abstraction of temporary storage
3. A storage system for the abstraction of permanent storage
4. An event dispatcher for observer/mediator patterns
5. Symfony Console Application for command-line tasks
6. A DataObject class generation system
7. Monolog for logging 

# Guides

* [Dependency Injection System](guides/dependency-injection.md)
* [State System](guides/state.md)
* [Storage System](guides/storage.md)
* [Event Dispatcher](guides/event-dispatcher.md)
* [Console Application](guides/console-application.md)
* [DataObject Generator](guides/dataobject-generator.md)
* [Monolog](guides/monolog.md)

# Requirements

* PHP 5.4
* SilverStripe 2.4
* Composer
* SilverStripe `_ss_environment.php` file

## Composer requirements

The installation of the following dependencies is handled by `composer`:

```
"symfony/event-dispatcher": "~2.3",
"symfony/dependency-injection": "~2.3",
"symfony/console": "~2.3",
"monolog/monolog": "~1.6",
"camspiers/shared-dependency-injection": "~0.4"
```

# Coding standards

Heystack follows the PSR-0, PSR-1 and PSR-2 standards.


