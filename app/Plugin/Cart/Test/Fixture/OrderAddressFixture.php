<?php
/**
 * OrderAddressFixture
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class OrderAddressFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'OrderAddress';

/**
 * Table
 *
 * @var array $table
 */
	public $table = 'order_addresses';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'order_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128),
		'company' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128),
		'street' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128),
		'street2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128),
		'city' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128),
		'zip' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128),
		'country' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 3),
		'state' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 16, 'comment' => 'billing or shipping'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'USER_INDEX' => array('column' => 'user_id'),
			'ORDER_INDEX' => array('column' => 'order_id'))
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 'order-address-1',
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
			'created' => '2012-12-12 12:12:12',
			'modified' => '2012-12-12 12:12:12',
		)
	);

}
