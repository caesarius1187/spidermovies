Installation
============

	cd YOUR-APP-FOLDER
	git submodule add git://github.com/burzum/Cart.git Plugin/Cart
	git submodule add git://github.com/cakedc/search.git Plugin/Search
	git submodule update --init

If you do not want to add it as submodule just clone it instead of doing submodule add

	git clone git://github.com/burzum/Cart.git

If you use another user model class in your application other than User you'll need to configure the plugin to use that model:

```php
Configure::write('Cart.models.User', 'AppUser');
```

Don't forget to load the plugin with boostrap true.

```php
CakePlugin::load('Cart', array(
    'bootstrap' => true,
));
```
