<?php
/**
 * Copyright 2009 - 2018, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2018, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * UserFixture
 *
 * @package users
 * @subpackage users.tests.fixtures
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
	public $fields = [
			'id' => ['type' => 'string', 'null' => false, 'length' => 36, 'key' => 'primary'],
			'username' => ['type' => 'string', 'null' => false, 'default' => null],
			'slug' => ['type' => 'string', 'null' => false, 'default' => null],
			'password' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 128],
			'password_token' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 128],
			'email' => ['type' => 'string', 'null' => true, 'default' => null],
			'email_verified' => ['type' => 'boolean', 'null' => true, 'default' => '0'],
			'email_token' => ['type' => 'string', 'null' => true, 'default' => null],
			'email_token_expires' => ['type' => 'datetime', 'null' => true, 'default' => null],
			'tos' => ['type' => 'boolean', 'null' => true, 'default' => '0'],
			'active' => ['type' => 'boolean', 'null' => true, 'default' => '0'],
			'last_action' => ['type' => 'datetime', 'null' => true, 'default' => null],
			'last_login' => ['type' => 'datetime', 'null' => true, 'default' => null],
			'is_admin' => ['type' => 'boolean', 'null' => true, 'default' => '0'],
			'role' => ['type' => 'string', 'null' => true, 'default' => null],
			'created' => ['type' => 'datetime', 'null' => true, 'default' => null],
			'modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
			'indexes' => [
				'PRIMARY' => ['column' => 'id', 'unique' => 1]]
	];

/**
 * Records
 *
 * @var array
 */
	public $records = [
		[
			'id' => '1',
			'username' => 'adminuser',
			'slug' => 'adminuser',
			'password' => 'test', // test
			'password_token' => 'testtoken',
			'email' => 'adminuser@cakedc.com',
			'email_verified' => 1,
			'email_token' => 'testtoken',
			'email_token_expires' => '2008-03-25 02:45:46',
			'tos' => 1,
			'active' => 1,
			'last_action' => '2008-03-25 02:45:46',
			'last_login' => '2008-03-25 02:45:46',
			'is_admin' => 1,
			'role' => 'admin',
			'created' => '2008-03-25 02:45:46',
			'modified' => '2008-03-25 02:45:46'
		],
		[
			'id' => '47ea303a-3cyc-k251-b313-4811c0a800bf',
			'username' => 'testuser',
			'slug' => 'testuser',
			'password' => 'secretkey', // secretkey
			'password_token' => '',
			'email' => 'testuser@cakedc.com',
			'email_verified' => '1',
			'email_token' => '',
			'email_token_expires' => '2008-03-25 02:45:46',
			'tos' => 1,
			'active' => 1,
			'last_action' => '2008-03-25 02:45:46',
			'last_login' => '2008-03-25 02:45:46',
			'is_admin' => 0,
			'role' => 'user',
			'created' => '2008-03-25 02:45:46',
			'modified' => '2008-03-25 02:45:46'
		],
		[
			'id' => '37ea303a-3bdc-4251-b315-1316c0b300fa',
			'username' => 'user1',
			'slug' => 'user1',
			'password' => 'newpass', // newpass
			'password_token' => '',
			'email' => 'testuser1@testuser.com',
			'email_verified' => 0,
			'email_token' => 'testtoken2',
			'email_token_expires' => '2008-03-28 02:45:46',
			'tos' => 0,
			'active' => 0,
			'last_action' => '2008-03-25 02:45:46',
			'last_login' => '2008-03-25 02:45:46',
			'is_admin' => 0,
			'role' => 'user',
			'created' => '2008-03-25 02:45:46',
			'modified' => '2008-03-25 02:45:46'
		],
		[
			'id' => '495e36a2-1f00-46b9-8247-58a367265f11',
			'username' => 'oidtest',
			'slug' => 'oistest',
			'password' => 'newpass', // newpass
			'password_token' => '',
			'email' => 'oidtest@testuser.com',
			'email_verified' => 0,
			'email_token' => 'testtoken2',
			'email_token_expires' => '2008-03-28 02:45:46',
			'tos' => 0,
			'active' => 0,
			'last_action' => '2008-03-25 02:45:46',
			'last_login' => '2008-03-25 02:45:46',
			'is_admin' => 0,
			'role' => 'user',
			'created' => '2008-03-25 02:45:46',
			'modified' => '2008-03-25 02:45:46'
		],
		[
			'id' => '315e36a2-1fxj-46b9-8247-58a367265f11',
			'username' => 'oidtest2',
			'slug' => 'oistest',
			'password' => 'newpass', // newpass
			'password_token' => '',
			'email' => 'oidtest2@testuser.com',
			'email_verified' => 0,
			'email_token' => 'testtoken2',
			'email_token_expires' => '2008-03-28 02:45:46',
			'tos' => 1,
			'active' => 1,
			'last_action' => '2008-03-25 02:45:46',
			'last_login' => '2008-03-25 02:45:46',
			'is_admin' => 0,
			'role' => 'user',
			'created' => '2008-03-25 02:45:46',
			'modified' => '2008-03-25 02:45:46'
		],
		[
			'id' => '515e36a2-5fjj-46b9-8247-584367265f11',
			'username' => 'resetuser',
			'slug' => 'resetuser',
			'password' => 'newpass', // newpass
			'password_token' => 'testtoken',
			'email' => 'resetuser@testuser.com',
			'email_verified' => 1,
			'email_token' => 'testtoken',
			'email_token_expires' => '2008-03-28 02:45:46',
			'tos' => 1,
			'active' => 1,
			'last_action' => '2008-03-25 02:45:46',
			'last_login' => '2008-03-25 02:45:46',
			'is_admin' => 0,
			'role' => 'user',
			'created' => '2008-03-25 02:45:46',
			'modified' => '2008-03-25 02:45:46'
		]
	];

/**
 * Constructor
 *
 */
	public function __construct() {
		parent::__construct();
		$this->User = ClassRegistry::init('Users.User');
		foreach ($this->records as &$record) {
			$record['password'] = $this->User->hash($record['password'], null, true);
		}
	}

}
