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

App::uses('Controller', 'Controller');
App::uses('RememberMeComponent', 'Users.Controller/Component');

/**
 * CookieComponentTestController class
 *
 * @package Cake.Test.Case.Controller.Component
 */
class RememberMeComponentTestController extends Controller {

/**
 * components property
 *
 * @var array
 */
	public $components = [
		'Users.RememberMe',
		'Auth'
	];
}

class RememberMeComponentTest extends CakeTestCase {

/**
 * Controller property
 *
 * @var CookieComponentTestController
 */
	public $Controller;

/**
 * User data
 * @var array 
 */
	public $usersData = [
		'test' => [
			'email' => 'test@cakedc.com',
			'password' => 'test'
		],
		'admin' => [
			'email' => 'admin@cakedc.com',
			'password' => 'admin'
		]
	];

/**
 * start
 *
 * @return void
 */
	public function setUp() {
		$_COOKIE = [];
		Configure::write('Config.language', 'eng');
		$this->request = new CakeRequest();
		$this->Controller = new RememberMeComponentTestController($this->request, new CakeResponse());
		$this->Controller->constructClasses();

		$this->RememberMe = $this->Controller->RememberMe;
		$this->RememberMe->Cookie = $this->getMockBuilder('CookieComponent')
			->setConstructorArgs([$this->Controller->Components])
			->getMock();
		$this->RememberMe->Auth = $this->getMockbuilder('AuthComponent')->setConstructorArgs([$this->Controller->Components])->getMock();
		$this->RememberMe->request = $this->request;
	}

/**
 * testSetCookie
 *
 * @return void
 */
	public function testSetCookie() {
		$this->RememberMe->Cookie->expects($this->once())
			->method('write')
			->with('rememberMe', [
				'email' => 'email',
				'password' => 'password'], true);

		$this->RememberMe->setCookie([
			'User' => [
				'email' => 'email',
				'password' => 'password']
			]
		);
	}

/**
 * testRestoreLoginFromCookie
 *
 * @return void
 */
	public function testRestoreLoginFromCookie() {
		$this->RememberMe->Cookie->expects($this->any())
			->method('read')
			->with($this->equalTo('rememberMe'))
			->will($this->returnValue($this->usersData['admin']));

		$this->RememberMe->Auth->expects($this->once())
			->method('login')
			->will($this->returnValue(true));

		$this->__setPostData(['User' => $this->usersData['test']]);

		$this->RememberMe->restoreLoginFromCookie();

		// even if we post "test" user, we have a remember me cookie set and will prioritize the cookie over the post
		// NOTE we check if the user is logged in in the startup method of the Component
		$this->assertEquals($this->RememberMe->request->data, [
			'User' => $this->usersData['admin']
		]);
	}

/**
 * testRestoreLoginFromCookieIncorrectLogin
 * 
 * We check the post request data is not modified when the cookie holds incorrect login credentials
 *
 * @return void
 */
	public function testRestoreLoginFromCookieIncorrectLogin() {
		// cookie will hold "admin" data, and post request will have "test"
		$this->RememberMe->Cookie->expects($this->any())
			->method('read')
			->with($this->equalTo('rememberMe'))
			->will($this->returnValue($this->usersData['admin']));
		// admin will not login
		$this->RememberMe->Auth->expects($this->once())
			->method('login')
			->will($this->returnValue(false));
		// post has "test" data
		$this->__setPostData(['User' => $this->usersData['test']]);
		$this->RememberMe->restoreLoginFromCookie();
		$this->assertEquals($this->RememberMe->request->data, [
			'User' => $this->usersData['test']]);
	}

/**
 * testDestroyCookie
 *
 * @return void
 */
	public function testDestroyCookie() {
		$_COOKIE['User'] = 'defined';
		$this->RememberMe->Cookie->expects($this->once())
			->method('destroy');
		$this->RememberMe->destroyCookie();
	}

/**
 * Set post data to the test controller
 *
 * @var array $data
 * @return void
 */
	private function __setPostData($data = []) {
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$this->RememberMe->request->data = $data;
	}
}
