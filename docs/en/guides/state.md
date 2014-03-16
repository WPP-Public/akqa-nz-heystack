# State System

The state system exists to provide an abstraction of user based state in your application. The state system
is a lot like a cache, only it is specific to a browser session.

The state system is used to avoid re-computing computationally expensive results, such as the total of a cart etc.

By default the PHP session is used to store state, but different state backends can be used.

Any Doctrine Cache provider (the cache system used by Heystack) can be used as a state cache.

To do this you need to provide a new service to the `Heystack\State\State` service (`state`) in your `mysite/config`

```yml
services:
	memcached:
		class: Memcached
		calls:
			- [ 'addServer', ['localhost', 11211] ]

	state_doctrine_cache:
		class: Doctrine\Common\Cache\MemcachedCache
		arguments: [ @memcached ]

	doctrine_backend:
		class: Heystack\State\Backends\DoctrineCache
		arguments: [ @state_doctrine_cache ]

	# The following are the key lines that set the state service to use the doctrine cache
	state:
		class: %state.class%
		arguments: [ @doctrine_backend ]
		tags:
			- { name: autoinject.provides, all: true }
```

In the previous example we have set up state to be stored in a memcache server. Interestingly, we could do this using
the existing session based implementation by utilizing the `memcached` session handler:

In your php.ini file or your `_ss_environment.php` file.

```
session.save_handler = memcached 
session.save_path = "localhost:11211"
```
