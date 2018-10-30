# Changelog

## 2013-08-23

 * Decoupled the checkout process from the cart, moved the related methods from the CartsController into the new CheckoutController
 * Added an event that is triggered when an order record is changed

## 2013-04-19

 * moved CartManager::_initializeCart() from initialize() to startup()
 * fixed the 2nd migration file
 * implemented merging of the cart session with the database cart after login
 * added a few more tests, need more