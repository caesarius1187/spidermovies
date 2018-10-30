<?php
class ChangesAndNewFields extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Dependency array. Define what minimum version required for other part of db schema
 *
 * Migration defined like 'app.m49ad0b91bd4c4bd482cc1de43461d00a' or 'plugin.PluginName.m49ad0d8518904f518db21bb43461d00a'
 * 
 * @var array $dependendOf
 * @access public
 */
	public $dependendOf = array();

/**
 * Shell object
 *
 * @var MigrationInterface
 * @access public
 */
	public $Shell;

/**
 * Migration array
 * 
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'orders' => array(
					'order_item_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
					'order_number' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64),
				),
				'carts_items' => array(
					'quantity_limit' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
				),
				'order_items' => array(
					'total' => array('type' => 'float', 'null' => false, 'default' => 0.00, 'length' => 10,2),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'orders' => array('order_item_count', 'order_number'),
				'order_items' => array('total'),
				'cart_items' => array('quantity_limit'),
			),
		),
	);

/**
 * before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean
 */
	public function before($direction) {
		return true;
	}

/**
 * after migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean
 */
	public function after($direction) {
		return true;
	}

}