<?php
App::uses('Security', 'Utility');
/**
 * User Fixture
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class UserFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'User';

/**
 * Table
 *
 * @var array $table
 */
	public $table = 'users';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null),
		'email' => array('type' => 'string', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1))
		);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'username' => 'user-1',
			'email' => 'user-1@carttest.com',
		),
	);

}
