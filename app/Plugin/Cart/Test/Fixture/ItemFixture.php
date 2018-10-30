<?php
/**
 * ItemFixture
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class ItemFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Item';

/**
 * Table
 *
 * @var array $table
 */
	public $table = 'items';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null),
		'price' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => 8.2),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => 0),
		'is_virtual' => array('type' => 'boolean', 'null' => false, 'default' => 0),
		'max_quantity' => array('type' => 'integer', 'null' => false, 'default' => 0),
		'min_quantity' => array('type' => 'integer', 'null' => false, 'default' => 0),
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
			'id' => 'item-1',
			'name' => 'Eizo Flexscan S2431W',
			'price' => '720.37',
			'active' => 1
		),
		array(
			'id' => 'item-2',
			'name' => 'CakePHP',
			'price' => '999.10',
			'active' => 1
		),
		array(
			'id' => 'item-3',
			'name' => 'Low quality code',
			'price' => '0.99',
			'active' => 1
		),
	);

}
