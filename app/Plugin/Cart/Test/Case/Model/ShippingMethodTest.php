<?php
App::uses('ShippingMethod', 'Cart.Model');

class ShippingMethodTestCase extends CakeTestCase {

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
		'plugin.Cart.ShippingMethod'
	);

/**
 * Test to run for the test case (e.g array('testFind', 'testView'))
 * If this attribute is not empty only the tests from the list will be executed
 *
 * @var array
 * @access protected
 */
	protected $_testsToRun = array();

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		$this->ShippingMethod = ClassRegistry::init('Cart.ShippingMethod');
		$fixture = new ShippingMethodFixture();
		$this->record = array('ShippingMethod' => $fixture->records[0]);
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ShippingMethod);
		ClassRegistry::flush();
	}

/**
 * testInstance
 *
 * @return void
 */
	public function testInstance() {
		$this->assertTrue(is_a($this->ShippingMethod, 'ShippingMethod'));
	}

}
