#Heyday Ecommerce

##Design

* Use interfaces for all things which interact with each other.
* Use the observer pattern (specifically for subsystems). [Symfony Event Dispatcher](http://symfony.com/doc/master/components/event_dispatcher/introduction.html)

###State

* State. State backends. Statable objects should implement the Serializable interface for lightweight saving
* Can have multiple backends, Session, Memcache, and DB
* Two different classes of state, fixed and dynamic. Fixed being save once, fixed forever (DB), dynamic being changable 
* Two different types of state, written state, which use a backend, and then php memory state, which is non-serialized things stored in statics

###Config

* Centralised system configuration storage.
* Storing gateway api keys/ids/urls for example
* Subsystems store "routing" information here

###Subsystems

* Like modules/modifers
* Save to State after processing.
* Process on user input, and on dispatched events (when listened to)
* Ability to listen to and dispatch events
* 