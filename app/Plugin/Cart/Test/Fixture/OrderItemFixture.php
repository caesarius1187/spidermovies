<?php
/**
 * OrderItemFixture
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class OrderItemFixture extends CakeTestFixture {
/**
 * Name
 *
 * @var string
 * @access public
 */
	public $name = 'OrderItem';

/**
 * Fields
 *
 * @var array
 * @access public
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'order_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'quantity' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'price' => array('type' => 'float', 'null' => true, 'default' => null),
		'virtual' => array('type' => 'boolean', 'null' => true, 'default' => '0', 'comment' => 'Virtual as a download or a service'),
		'status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 16, 'collate' => 'utf8_general_ci', 'comment' => 'internal status, up to the app', 'charset' => 'utf8'),
		'shipped' => array('type' => 'boolean', 'null' => true, 'default' => '0', 'comment' => 'Virtual as a download or a service'),
		'shipping_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'total' => array('type' => 'float', 'null' => false, 'default' => 0.00, 'length' => 10,2),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
	);

/**
 * Records
 *
 * @var array
 * @access public
 */
	public $records = array(
		array(
			'id' => '500dc3b8-a198-44cf-a13d-79e0e1a32268',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'quantity' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 1,
			'virtual' => 1,
			'status' => 'Lorem ipsum do',
			'shipped' => 1,
			'shipping_date' => '2012-07-23 23:35:52',
			'created' => '2012-07-23 23:35:52',
			'modified' => '2012-07-23 23:35:52'
		),
		array(
			'id' => '500dc3b8-0e30-4a00-a078-79e0e1a32268',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'quantity' => 2,
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 2,
			'virtual' => 1,
			'status' => 'Lorem ipsum do',
			'shipped' => 1,
			'shipping_date' => '2012-07-23 23:35:52',
			'created' => '2012-07-23 23:35:52',
			'modified' => '2012-07-23 23:35:52'
		),
		array(
			'id' => '500dc3b8-6678-45c9-a615-79e0e1a32268',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'quantity' => 3,
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 3,
			'virtual' => 1,
			'status' => 'Lorem ipsum do',
			'shipped' => 1,
			'shipping_date' => '2012-07-23 23:35:52',
			'created' => '2012-07-23 23:35:52',
			'modified' => '2012-07-23 23:35:52'
		),
		array(
			'id' => '500dc3b8-be5c-4f5b-9c8d-79e0e1a32268',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'quantity' => 4,
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 4,
			'virtual' => 1,
			'status' => 'Lorem ipsum do',
			'shipped' => 1,
			'shipping_date' => '2012-07-23 23:35:52',
			'created' => '2012-07-23 23:35:52',
			'modified' => '2012-07-23 23:35:52'
		),
		array(
			'id' => '500dc3b8-1898-4b80-a2ba-79e0e1a32268',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'quantity' => 5,
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 5,
			'virtual' => 1,
			'status' => 'Lorem ipsum do',
			'shipped' => 1,
			'shipping_date' => '2012-07-23 23:35:52',
			'created' => '2012-07-23 23:35:52',
			'modified' => '2012-07-23 23:35:52'
		),
		array(
			'id' => '500dc3b8-7018-42ad-8cec-79e0e1a32268',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'quantity' => 6,
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 6,
			'virtual' => 1,
			'status' => 'Lorem ipsum do',
			'shipped' => 1,
			'shipping_date' => '2012-07-23 23:35:52',
			'created' => '2012-07-23 23:35:52',
			'modified' => '2012-07-23 23:35:52'
		),
		array(
			'id' => '500dc3b8-c798-4ead-97cc-79e0e1a32268',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'quantity' => 7,
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 7,
			'virtual' => 1,
			'status' => 'Lorem ipsum do',
			'shipped' => 1,
			'shipping_date' => '2012-07-23 23:35:52',
			'created' => '2012-07-23 23:35:52',
			'modified' => '2012-07-23 23:35:52'
		),
		array(
			'id' => '500dc3b8-1fe0-4bc9-b313-79e0e1a32268',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'quantity' => 8,
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 8,
			'virtual' => 1,
			'status' => 'Lorem ipsum do',
			'shipped' => 1,
			'shipping_date' => '2012-07-23 23:35:52',
			'created' => '2012-07-23 23:35:52',
			'modified' => '2012-07-23 23:35:52'
		),
		array(
			'id' => '500dc3b8-8e6c-4684-8b91-79e0e1a32268',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'quantity' => 9,
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 9,
			'virtual' => 1,
			'status' => 'Lorem ipsum do',
			'shipped' => 1,
			'shipping_date' => '2012-07-23 23:35:52',
			'created' => '2012-07-23 23:35:52',
			'modified' => '2012-07-23 23:35:52'
		),
		array(
			'id' => '500dc3b8-ec2c-4171-9b35-79e0e1a32268',
			'order_id' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 'Lorem ipsum dolor sit amet',
			'model' => 'Lorem ipsum dolor sit amet',
			'quantity' => 10,
			'name' => 'Lorem ipsum dolor sit amet',
			'price' => 10,
			'virtual' => 1,
			'status' => 'Lorem ipsum do',
			'shipped' => 1,
			'shipping_date' => '2012-07-23 23:35:52',
			'created' => '2012-07-23 23:35:52',
			'modified' => '2012-07-23 23:35:52'
		),
	);

}
