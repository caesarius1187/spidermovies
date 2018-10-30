<?php
class FixingOrdersTableCartSnapshotField extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'rename_field' => array(
				'orders' => array(
					'cart_snapshop' => 'cart_snapshot'
				),
			),
		),
		'down' => array(
			'rename_field' => array(
				'orders' => array(
					'cart_snapshot' => 'cart_snapshop'
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 */
	public function after($direction) {
		return true;
	}

}
