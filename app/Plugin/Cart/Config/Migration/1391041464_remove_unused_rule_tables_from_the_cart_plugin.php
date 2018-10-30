<?php
class RemoveUnusedRuleTablesFromTheCartPlugin extends CakeMigration {

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
			'drop_table' => array(
				'cart_rules',
				'cart_rule_conditions'
			)
		),
		'down' => array(
			'create_table' => array(
				'cart_rules' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
					'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 255),
					'description' => array('type' => 'string', 'null' => true, 'default' => null),
					'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 255, 'comment' => 'tax, discount'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1)
					)
				),
				'cart_rule_conditions' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
					'cart_rule_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 36),
					'position' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => 2),
					'applies_to' => array('type' => 'string', 'null' => true, 'default' => null, 'comment' => 'cart, items'),
					'conditions' => array('type' => 'text', 'null' => true, 'default' => null),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1))
				)
			)
		)
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
