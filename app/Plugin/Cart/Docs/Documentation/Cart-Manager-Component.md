### Cart Manager Component

The Cart Manager is a component thought to capture post and get requests to a specified action, by default "buy" and add the result of this to the cart.

The Session, Cookie and Database Storage of the Cart Manager is pretty much decoupled.

### Sample Application

There is a sample application that will demonstrate how to use the cart plugin.

	http://github.com/burzum/CartSampleApp

Check it out, clone the application, run migrations, access the app. It already has a sample user and some sample items. Read the instructions of the plugin.

## How it works

When an user is not logged in the all cart items will be added to the session.

When the user logins in the session gets merged with the users currently active cart. If not cart was present in the database before it is created.

For a logged in user the cart session data is always kept in sync with the database to preserve the items when the user comes back later.

The CartManager will deal with all of this and make sure that items get added to the session and the configured cart model and the associated cart items table.

## How to do I extend or modify the cart?

There two ways to modify the cart, depending on the kind of changes one might be better than the other one for your case.

You can simply do regular OOP an inherit the plugin classes on application level or you fork the plugin on github and do your modifications in your fork.

It is highly recommended that if you fork it you do your changes in a branch and do not work in master or develop so that you can do easy pull request and it is easier for yourself to compare the branches then and to do merges from the original repository.
