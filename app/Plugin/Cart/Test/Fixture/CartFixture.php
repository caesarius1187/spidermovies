<?php
/**
 * CartFixture
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class CartFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Cart';

/**
 * Table
 *
 * @var array $table
 */
	public $table = 'carts';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'total' => array('type' => 'float', 'null' => true, 'default' => null),
		'active' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'item_count' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 6),
		'additional_data' => array('type' => 'text', 'null' => true, 'default' => null, 'comment' => 'For serialized data'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		)
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 'cart-1',
			'user_id' => 'user-1',
			'name' => 'Default Cart',
			'total' => 720.37,
			'active' => 1,
			'item_count' => 1,
			'additional_data' => array(),
			'created' => '2012-01-01 12:12:12',
			'modified' => '2012-01-01 12:12:12'
		),
		array(
			'id' => 'cart-2',
			'user_id' => 'user-1',
			'name' => 'Second Cart',
			'total' => '1000.00',
			'active' => 0,
			'item_count' => 3,
			'additional_data' => array(),
			'created' => '2012-01-01 12:12:12',
			'modified' => '2012-01-01 12:12:12'
		),
		array(
			'id' => 'cart-3',
			'user_id' => 'user-2',
			'name' => 'Default Cart',
			'total' => '1000.00',
			'active' => 0,
			'item_count' => 1,
			'additional_data' => array(),
			'created' => '2012-01-01 12:12:12',
			'modified' => '2012-01-01 12:12:12'
		),
	);

/**
 * Constructor
 *
 * @return CartFixture
 */
	public function __construct() {
		parent::__construct();
		foreach ($this->records as &$record) {
			$record['additional_data'] = serialize($record['additional_data']);
		}
	}

}

