Quick start
===========

This is an explanation of how getting it set up pretty quick without customization. If you want you can customize everything, take a look at the code and documentation for details.

In app/Config/boostrap.php
--------------------------

```php
CakePlugin::load('Cart', array(
	'bootstrap' => true,
));
```

You can as well copy the code from that file into your own bootstrap.php and replace the event listener that is used there with your own. The event listener will give you a lot of flexibility in changing the cart as you like.

In your AppController
---------------------

```php
class AppController extends Controller {

	public $components = array(
		'Cart.CartManager'
	);

	public $helpers = array(
		'Cart.Cart'
	);

	public function beforeRender() {
		parent::beforeRender();
	}
}
```

In your model
-------------

```php
class Product extends AppModel {

	public $actsAs = array(
		'Cart.Buyable'
	);
}
```

In your views:
--------------

Buy things via GET

```php
echo $this->Cart->link(__('buy me'), array(
	'item' => $item['Item']['id']
));
```

Buy things via POST

```php
echo $this->Form->create('Product', array(
	'url' => array(
		'action' => 'buy'
	)
));
echo $this->Form->input('quantity', array(
	'label' => false,
	'default' => 1
));
echo $this->Form->submit(__('buy me'), array(
	'div' => false
));
echo $this->Form->hidden('foreign_key', array(
	'value' => $product['Product']['id']
));
echo $this->Form->hidden('model', array(
	'value' => 'Product'
));
```
