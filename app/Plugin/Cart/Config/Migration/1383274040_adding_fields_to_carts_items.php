<?php
class AddingFieldsToCartsItems extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'carts_items' => array(
					'additional_data' => array('type' => 'text', 'null' => true, 'default' => null, 'comment' => 'For serialized data'),
				),
				'order_items' => array(
					'additional_data' => array('type' => 'text', 'null' => true, 'default' => null, 'comment' => 'For serialized data'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'order_items' => array(
					'additional_data'
				),
				'cart_items' => array(
					'additional_data'
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
