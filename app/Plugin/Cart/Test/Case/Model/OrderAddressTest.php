<?php
App::uses('OrderAddress', 'Cart.Model');

class OrderAddressTestCase extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Cart.OrderAddress',
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
		$this->OrderAddress = ClassRegistry::init('Cart.OrderAddress');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrderAddress);
		ClassRegistry::flush();
	}

/**
 * testInstance
 *
 * @return void
 */
	public function testInstance() {
		$this->assertTrue(is_a($this->OrderAddress, 'OrderAddress'));
	}

/**
 * testFindDuplicate
 *
 * @return void
 */
	public function testFindDuplicate() {
		$data = array(
			'OrderAddress' => array(
				'order_id' => null,
				'user_id' => null,
				'first_name' => 'Florian',
				'last_name' => 'Krämer',
				'company' => 'CakeDC',
				'street' => 'Programmers Road 51',
				'street2' => '',
				'city' => 'German Town',
				'country' => 'DEU',
				'state' => 'HE',
				'type' => 'billing',
			)
		);
		$result = $this->OrderAddress->findDuplicate($data);
		$this->assertEquals($result, 'order-address-1');
	}

}
