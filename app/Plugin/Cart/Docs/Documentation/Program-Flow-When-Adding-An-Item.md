Program Flow when adding an Item
================================

CartManager::startup()
----------------------

If the component is configured to catch the buy action by the `buyAction` option and if the controller method of the same name *doesn't* exist, it will call CartManager::captureBuy(), otherwise `CartManger::addItem()` has to be called manually in your code.

CartManager::captureBuy()
-------------------------

* `captureBuy()` will detect if the item is added via POST or GET and call `getBuy()` or `postBuy()`.
* If any of both returned false `captureBuy()` will return false as well and stop processing the request

CartManager::addItem()
----------------------

* If the quantity of the item is 0 it will remove the item instead of adding it
* The event `CartManager.beforeAddItem`is fired, if the result of the event is false or if it is stopped false is returned
* The model of the passed item is instantiated and `Model::hasMethod()` is called for `isBuyable` and `beforeAddToCart`
 * Both methods can be provided by the BuyableBehavior or directly implemented in the model
* `isBuyable()` is called on the item model, if false is returned from it addItem() returns false
 * See 1)
* The item models method `beforeAddToCart()` is called
 * See 2)
* If the user is logged in the cart data gets written to the DB
* The session data is updated
* The cart is recalculated with the new item included
* The event `CartManager.afterAddItem ` is fired

1) Model::isBuyable() / BuyableBehavior::isBuyable()
----------------------------------------------------

`isBuyable()` in the behavior is implemented to detect if an item can be bought at all. By default the behavior just checks if the record is present by using the id provided by the passed item data.

You can override this method in your model to implement custom logic here, like checking if the item is available at all or is out of stock for example.

2) Model::beforeAddToCart() / BuyableBehavior::beforeAddToCart()
----------------------------------------------------------------

By default the behavior method just gets the record of the item without any associated data and passes the result to composeItemData(), see 3).

You can override this method in your model to fetch more data if you need to.

3) Model::composeItemData() / BuyableBehavior::composeItemData()
----------------------------------------------------------------

TBD