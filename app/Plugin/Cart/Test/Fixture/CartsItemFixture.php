<?php
/**
 * CartsItemFixture
 *
 */
class CartsItemFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'CartsItem';

/**
 * Table
 *
 * @var array $table
 */
	public $table = 'carts_items';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'cart_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'foreign_key' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64),
		'quantity' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'price' => array('type' => 'float', 'null' => true, 'default' => null),
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
			'id' => 'carts-item-1',
			'cart_id' => 'cart-1',
			'foreign_key' => 'item-1',
			'model' => 'Item',
			'quantity' => 1,
			'name' => 'Eizo Flexscan S2431W',
			'price' => 720.37,
			'additional_data' => array(),
			'created' => '2012-01-01 12:30:00',
			'modified' => '2012-01-01 12:30:00',
		),
		array(
			'id' => 'carts-item-2',
			'cart_id' => 'cart-2',
			'foreign_key' => 'item-2',
			'model' => 'Item',
			'quantity' => 2,
			'name' => 'CakePHP',
			'price' => 999.10,
			'additional_data' => array(),
			'created' => '2012-01-01 12:30:00',
			'modified' => '2012-01-01 12:30:00',
		),
		array(
			'id' => 'carts-item-3',
			'cart_id' => 'cart-2',
			'foreign_key' => 'item-3',
			'model' => 'Item',
			'quantity' => 15,
			'name' => 'Low quality code',
			'price' => 0.99,
			'additional_data' => array(),
			'created' => '2012-01-01 12:30:00',
			'modified' => '2012-01-01 12:30:00',
		),
	);

/**
 * Constructor
 *
 * @return CartsItemFixture
 */
	public function __construct() {
		parent::__construct();
		foreach ($this->records as &$record) {
			$record['additional_data'] = serialize($record['additional_data']);
		}
	}

}
