#Heyday Ecommerce

##Design

* Use interfaces for all things which interact with each other.
* Use the observer pattern (specifically for subsystems). [Symfony Event Dispatcher](http://symfony.com/doc/master/components/event_dispatcher/introduction.html)

###State

* State. State backends. Statable objects should implement the Serializable interface for lightweight saving
* Can have multiple backends, Session, Memcache, and DB
* Two different classes of state, fixed and dynamic. Fixed being save once, "fixed forever" (DB), dynamic being changable 
* Two different types of state, written state, which use a backend, and then php memory state, which is non-serialized things stored in statics

###Config

* Centralised system configuration storage.
* Storing gateway api keys/ids/urls for example
* Subsystems store "routing" information here

###Subsystems

* Like modules/modifers
* Shipping, taxes, weta dollars
* Save to State after processing.
* Process on user input, and on dispatched events (when listened to)
* Ability to listen to and dispatch events

###Purchasable

* An interface that different objects can implement to become purchasable

###Controllers

* One controller to rule them all!

###Purchasablesholder

* Is like a order
* Has State
* State includes subsystems
* State includes purchaseables
* Maybe a subsystem just like another subsystem

* has * e.g. has discounts.
* If something is a discount, how is it defined as a discount, and how do we introduce new "notions" like discounts, e.g. increases.

* Remove dependency of payment to order, payment more relates to a cart (because even if an cart is split into multiple orders, from warehouse/preorder logic the user only pays once)
* Purchasablesholder and order are different. Because Order is a business specific thing, having to do with invoicing, shippping, reporting, whereas Cart/Purchasable holder is more of a user based thing, what the user pays for.

* Different types of totals, adding and discount different subsystems
* Perhaps as TotalsView type object that is an aggregation of subsystems.

* subsystem defines surfaced data, at a configurations level

* OrderDecorator - why? additional functionality to Orders prior to them becoming shipping orders, where custom functionality could be nicely developed
* Information about billing
* Historical currency exchanges
* Currency of order
* Different order statuses specific to  payment type?
* Payments for order
* Comments on order post payment


* ShippingOrder - because we needed to match the purchasables to  available shipping warehouse, i.e. orders needed to be split into separate orders based on warehouse or preorders





###Functionality to cover architecturally

* Cart needs to be able to be saved to the database for later. Thought this should be able to be developed as a subsystem/module/configuration
* Multiple currencies


