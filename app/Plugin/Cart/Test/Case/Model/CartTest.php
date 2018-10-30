<?php
App::uses('Cart', 'Cart.Model');
App::uses('DefaultCartEventListener', 'Cart.Event');

/**
 * Cart Test
 *
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 *
 * @property Cart Cart
 */
class CartTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Cart.Cart',
		'plugin.Cart.CartsItem',
		'plugin.Cart.Item',
		'plugin.Cart.Order',
		'plugin.Cart.User',
	);

/**
 * startUp
 *
 * @return void
 */
	public function setUp() {
		$this->Cart = ClassRegistry::init('Cart.Cart');
		$this->_detachAllListeners();
		CakeEventManager::instance()->attach(new DefaultCartEventListener());
	}

/**
 * Detaches all listeners from the Cart events to avoid application level events changing the tests
 *
 * @return void
 */
	protected function _detachAllListeners() {
		$EventManager = CakeEventManager::instance();
		$events = array(
			'Cart.beforeCalculateCart',
			'Cart.applyTaxRules',
			'Cart.applyDiscounts',
			'Cart.afterCalculateCart'
		);
		foreach ($events as $event) {
			$listeners = $EventManager->listeners($event);
			foreach ($listeners as $listener) {
				foreach ($listener['callable'] as $callable) {
					$EventManager->detach($callable);
				}
			}
		}
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		ClassRegistry::flush();
		unset($this->Model);
	}

/**
 * testInstance
 *
 * @return void
 */
	public function testInstance() {
		$this->assertTrue(is_a($this->Cart, 'Cart'));
	}

/**
 * testView
 *
 * @return void
 */
	public function testView() {
		$result = $this->Cart->view('cart-1');
		$this->assertTrue(is_array($result));
		$this->assertEquals($result['Cart']['id'], 'cart-1');
	}

/**
 * testView
 *
 * @expectedException NotFoundException
 * @return void
 */
	public function testViewNotFoundException() {
		$this->Cart->view('invalid-cart-id');
	}

/**
 * testAddItem
 *
 * @return void
 */
	public function testAddItem() {
		$this->Cart->cartId = 1;
		$result = $this->Cart->addItem('cart-1',
			array(
				'name' => 'Eizo Flexscan S2431W',
				'model' => 'Item',
				'foreign_key' => 'item-1',
				'quantity' => 1,
				'price' => 52.00,
			)
		);
		$this->assertTrue(is_array($result));
		$this->assertEquals($result['CartsItem']['model'], 'Item');
		$this->assertEquals($result['CartsItem']['foreign_key'], 'item-1');
		$this->assertEquals($result['CartsItem']['quantity'], 1);
		$this->assertEquals($result['CartsItem']['price'], 52.00);
	}

/**
 * testIsActive
 *
 * @return void
 */
	public function testIsActive() {
		$result = $this->Cart->isActive('cart-1');
		$this->assertTrue($result);
		$result = $this->Cart->isActive('cart-2');
		$this->assertFalse($result);
	}

/**
 * testSetActive
 *
 * @return void
 */
	public function testSetActive() {
		$result = $this->Cart->setActive('cart-1', 'user-1');
		$this->assertFalse($result);
		$result = $this->Cart->setActive('cart-2', 'user-1');
		$this->assertTrue($result);
	}

/**
 * testSetActiveNotFoundException
 *
 * @expectedException NotFoundException
 * @return void
 */
	public function testSetActiveNotFoundException() {
		$this->Cart->setActive('invalid-cart', 'user-1');
	}

/**
 * testGetActive
 *
 * @return void
 */
	public function testGetActive() {
		$expected = array(
			'Cart' => array(
				'id' => 'cart-1',
				'user_id' => 'user-1',
				'name' => 'Default Cart',
				'total' => '720.37',
				'active' => true,
				'item_count' => '1',
				'created' => '2012-01-01 12:12:12',
				'modified' => '2012-01-01 12:12:12',
				'additional_data' => array()
			),
			'CartsItem' => array(
				0 => array(
					'id' => 'carts-item-1',
					'cart_id' => 'cart-1',
					'foreign_key' => 'item-1',
					'model' => 'Item',
					'quantity' => '1',
					'name' => 'Eizo Flexscan S2431W',
					'price' => '720.37',
					'created' => '2012-01-01 12:30:00',
					'modified' => '2012-01-01 12:30:00',
					'additional_data' => 'a:0:{}'
				)
			)
		);
		$result = $this->Cart->getActive('user-1');
		$this->assertEquals($result, $expected);

		$result = $this->Cart->getActive('user-does-not-exist', false);
		$this->assertFalse($result);
	}

/**
 * testRequiresShipping
 *
 * @return void
 */
	public function testRequiresShipping() {
		$items = array(
			array('foobar' => 'test'));
		$this->assertTrue($this->Cart->requiresShipping($items));

		$items = array(
			array('virtual' => 0));
		$this->assertTrue($this->Cart->requiresShipping($items));

		$items = array(
			array('virtual' => 1));
		$this->assertFalse($this->Cart->requiresShipping($items));
	}

/**
 * testCalculateTotals
 *
 * @return void
 */
	public function testCalculateTotals() {
		$cart = array(
			'CartsItem' => array(
				array('price' => 12.01, 'quantity' => 2),
				array('price' => 21.10, 'quantity' => 1)
			)
		);
		$result = $this->Cart->calculateTotals($cart);
		$this->assertEquals($result['Cart']['total'], 45.12);
	}

/**
 * testSyncWithSessionData
 *
 * @return void
 */
	public function testMergeItems() {
		$itemsBefore = $this->Cart->CartsItem->find('all', array(
			'contain' => array(),
			'conditions' => array(
				'cart_id' => 'cart-1')));

		$itemCount = count($itemsBefore);
		$sessionData = array(
			'Cart' => array(),
			'CartsItem' => array(
				// A new item to merge in
				array(
					'name' => 'CakePHP',
					'model' => 'Item',
					'foreign_key' => 'item-2',
					'quantity' => 1,
					'price' => 999.10
				),
				// Update an existing items quantity by +1
				array(
					'model' => 'Item',
					'foreign_key' => 'item-1',
					'quantity' => 2,
					'price' => 12
				)
			)
		);

		$result = $this->Cart->mergeItems('cart-1', $sessionData['CartsItem']);
		$this->assertEquals($result, 1);

		$itemsAfter = $this->Cart->CartsItem->find('all', array(
			'contain' => array(),
			'conditions' => array(
				'cart_id' => 'cart-1'
			)
		));

		// Sort the items by name so that we can be sure an item is at a certain index in the array
		$itemsAfter = Hash::sort($itemsAfter, '{n}.CartsItem.name', 'desc');

		$this->assertEquals(count($itemsAfter), $itemCount + 1);
		$this->assertEquals($itemsAfter[0]['CartsItem']['name'], 'Eizo Flexscan S2431W');
		$this->assertEquals($itemsAfter[0]['CartsItem']['quantity'], 2);
	}

/**
 * testAdd
 *
 * @return void
 */
	public function testAdd() {
		$data = array(
			'Cart' => array(
				'name' => 'test'
			)
		);
		$result = $this->Cart->add($data, 'user-1');
		$this->assertTrue($result);
	}

/**
 * testCalculateCart
 *
 * @return void
 */
	public function testCalculateCart() {
		$data = array(
			'Cart' => array(
				'id' => 'cart-1',
				'active' => true,
				'item_count' => '2',
			),
			'CartsItem' => array(
				0 => array(
					'id' => 'carts-item-1',
					'cart_id' => 'cart-1',
					'foreign_key' => 'item-1',
					'model' => 'Item',
					'quantity' => '1',
					'name' => 'Eizo Flexscan S2431W',
					'price' => '720.37',
				),
				1 => array(
					'id' => 'carts-item-2',
					'cart_id' => 'cart-1',
					'foreign_key' => 'item-2',
					'model' => 'Item',
					'quantity' => '15',
					'name' => 'Some other Item',
					'price' => '0.59',
				),
			)
		);

		$result = $this->Cart->calculateCart($data);
		$this->assertEquals($result['Cart']['total'], 729.22);
	}

}