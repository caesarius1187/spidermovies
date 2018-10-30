<?php
/**
 * OrderFixture
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class OrderFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Order';

/**
 * Table
 *
 * @var array $table
 */
	public $table = 'orders';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'cart_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'shipping_address_id' => array('type' => 'string', 'null' => true, 'default' => null, 'lenght' => 36),
		'billing_address_id' => array('type' => 'string', 'null' => true, 'default' => null, 'lenght' => 36),
		'cart_snapshot' => array('type' => 'text', 'null' => true, 'default' => null),
		'token' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32),
		'processor' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32),
		'status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 16, 'comment' => 'internal status, up to the app'), // completed, refunded, partial-refund, cancelled, shipped
		'payment_reference' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 16, 'status of the transaction'),
		'payment_status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 16, 'status of the transaction'),
		'transaction_fee' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => 6,2),
		'invoice_number' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64),
		'billing_address' => array('type' => 'text', 'null' => true, 'default' => null),
		'shipping_address' => array('type' => 'text', 'null' => true, 'default' => null),
		'total' => array('type' => 'float', 'null' => true, 'default' => null),
		'shipping_rate' => array('float' => 'string', 'null' => false, 'default' => 0.00),
		'gross' => array('type' => 'float', 'null' => false, 'default' => 0.00),
		'currency' => array('type' => 'integer', 'null' => true, 'default' => null),
		'order_item_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'order_number' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'USER_INDEX' => array('column' => 'user_id'),
			'CART_INDEX' => array('column' => 'cart_id'),
			'TOKEN_INDEX' => array('column' => 'token'))
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 'order-1',
			'user_id' => 'user-1',
			'cart_id' => null,
			'shipping_address_id' => null,
			'billing_address_id' => null,
			'cart_snapshot' => array(
				'Cart' => array(

				),
				'CartsItem' => array(

				)
			),
			'token' => 'token-1',
			'processor' => 'Paypal',
			'status' => 'pending',
			'payment_reference' => '123456',
			'payment_status' => 'pending',
			'transaction_fee' => 0.51,
			'billing_address' => null,
			'invoice_number' => '20120101-1',
			'shipping_address' => null,
			'total' => 12.00,
			'shipping_rate' => 0.00,
			'gross' => 0.00,
			'currency' => 'EUR',
			'order_item_count' => 0,
			'order_number' => 1,
			'created' => '2012-01-01 12:12:12',
			'modified' => '2012-01-01 12:12:12',
		),
	);

/**
 * Constructor
 *
 * @return OrderFixture
 */
	public function __construct() {
		parent::__construct();
		foreach ($this->records as &$record) {
			$record['cart_snapshot'] = serialize($record['cart_snapshot']);
		}
	}

}
