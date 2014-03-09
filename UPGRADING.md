# 3.0.0

* DBClosureLoader now uses a DataList $resource
* New mechanism for loading extensions and compiler passes due to remove dependency on camspiers/shared-dependency-injection
* Monolog is no longer a dependency in favor of psr/log and a logger being provided in user space
* No longer attempt to load configs from heystack/config in favour of only supporting config defined in mysite
* Removed DataObjectHandler in favor of directly using the SilverStripe ORM
* Removed the use of ContainerExtensionConfigProcessor in favor of using container extensions with explicitly required properties
* Monolog is no longer a dependency. Dependency is now on psr/log
* Removed ServiceStore as SilverStripe now supports heystack dependencies via "%$heystack.*" in Injector component
* Replaced "ss_orm_backend.data_provider" tag with "ss_orm_backend.reference_data_provider"
* Removed "related" config in storage and generate schema
