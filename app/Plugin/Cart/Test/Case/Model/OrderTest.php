<?php
App::uses('Order', 'Cart.Model');
/**
 * OrderTest
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class OrderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Cart.Cart',
		'plugin.Cart.Item',
		'plugin.Cart.Order',
		'plugin.Cart.OrderItem',
		'plugin.Cart.CartsItem',
		'plugin.Cart.OrderAddress',
		'plugin.Cart.User',
	);

/**
 * startUp
 *
 * @return void
 */
	public function setUp() {
		$this->Order = ClassRegistry::init('Cart.Order');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		ClassRegistry::flush();
		unset($this->Order);
	}

/**
 * testInstance
 *
 * @return void
 */
	public function testInstance() {
		$this->assertTrue(is_a($this->Order, 'Order'));
	}

/**
 * testView
 *
 * @return void
 */
	public function testView() {
		$result = $this->Order->view('order-1', 'user-1');
		$this->assertTrue(is_array($result) && !empty($result));
		$this->assertEquals($result['Order']['user_id'], 'user-1');
		$this->assertEquals($result['Order']['id'], 'order-1');
	}

/**
 * testViewNotFoundException
 *
 * @expectedException NotFoundException
 */
	public function testViewNotFoundException() {
		$this->Order->view('invalid-order', 'user-1');
	}

/**
 * testCreateSimpleOrder
 *
 * @return void
 */
	public function testCreateSimpleOrder() {
		$cartData = array(
			'Cart' => array(
				'id' => 'cart-1',
				'requires_shipping' => false,
				'user_id' => 'user-1',
				'processor' => 'Paypal.PaypalExpress',
				'total' => 20.95,
				//'additional_data' => array()
			),
			'CartsItem' => array(
				array(
					'name' => 'CakePHP',
					'foreign_key' => 'item-1',
					'model' => 'Item',
					'quantity' => 1,
					'price' => 10
				),
				array(
					'name' => 'Developer',
					'foreign_key' => 'item-2',
					'model' => 'Item',
					'quantity' => 2,
					'price' => 10
				)
			)
		);

		$cartData['Order'] = $cartData['Cart'];

		$result = $this->Order->createOrder($cartData);
		$this->assertTrue($result);
		$this->assertTrue(!empty($this->Order->data['Order']['id']));
	}

/**
 * testOrderNumber
 *
 * @return void
 */
	public function testOrderNumber() {
		$count = $this->Order->find('count');

		$this->Order->create();
		$this->Order->save(array(
			'Order' => array(
				'create' => '2066-12-12 12:12:12')),
			array(
				'validate' => false,
				'callbacks' => true));

		$result = $this->Order->orderNumber(array());
		$this->assertEquals($count + 1, $result);

		$this->Order->create();
		$this->Order->save(array(
			'Order' => array(
				'create' => '2066-12-12 12:12:15')),
			array(
				'validate' => false,
				'callbacks' => true));

		$result = $this->Order->orderNumber(array());
		$this->assertEquals($count + 2, $result);
	}

/**
 * testInvoiceNUmber
 *
 * @return void
 */
	public function testInvoiceNUmber() {
		$this->Order->create();
		$this->Order->save(array(
			'Order' => array(
				'create' => '2066-12-12 12:12:12')),
			array(
				'validate' => false,
				'callbacks' => true
			)
		);

		$result = $this->Order->invoiceNumber(array(), '2066-12-12');
		$this->assertEquals('20661212-1', $result);

		$this->Order->create();
		$this->Order->save(array(
			'Order' => array(
				'create' => '2066-12-12 12:12:15')),
			array(
				'validate' => false,
				'callbacks' => true
			)
		);

		$result = $this->Order->invoiceNumber(array(), '2066-12-12');

		$this->assertEquals('20661212-2', $result);

		$this->Order->create();
		$this->Order->save(array(
			'Order' => array(
				'create' => '2066-12-12 12:12:15')),
			array(
				'validate' => false,
				'callbacks' => true
			)
		);

		$result = $this->Order->invoiceNumber(array(), '2066-12-12');
		$this->assertEquals('20661212-3', $result);
	}

/**
 * testSameAsBilling
 *
 * @return void
 */
	public function shippingIsSameAsBilling() {
		$data = array(
			'BillingAddress' => array(
				'first_name' => 'John',
				'last_name' => 'Doe',
				'street' => 'First Street',
				'street2' => 'Second Street',
				'city' => 'Las Vegas',
				'state' => 'NV',
				'zip' => '25252',
				'country' => 'US'
			),
			'ShippingAddress' => array(
				'same_as_billing' => '1',
				'first_name' => '',
				'last_name' => '',
				'street' => '',
				'street2' => '',
				'city' => '',
				'state' => 'NV',
				'zip' => '',
				'country' => 'US'
			),
		);
		$result = $this->Order->sameAsBilling($data);
		$this->assertTrue($result);

		$data['ShippingAddress']['same_as_billing'] = '0';
		$result = $this->Order->sameAsBilling($data);
		$this->assertFalse($result);

		$data = array();
		$result = $this->Order->sameAsBilling($data);
		$this->assertFalse($result);
	}

}
