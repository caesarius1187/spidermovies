<?php
App::uses('Controller', 'Controller');
App::uses('CartManagerComponent', 'Cart.Controller/Component');
App::uses('AuthComponent', 'Controller/Component');

/**
 * CartTestItemsController
 *
 */
class CartTestItemsController extends Controller {
	public $uses = array('Item');
	public $modelClass = 'Item';
}

/**
 * Cart Manager Component Test
 * 
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class CartManagerComponentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Cart.Cart',
		'plugin.Cart.Item',
		'plugin.Cart.Order',
		'plugin.Cart.CartsItem',
	);

/**
 * startTest
 *
 * @return void
 */
	public function setUp() {
		$this->Controller = new CartTestItemsController($this->getMock('CakeRequest'), $this->getMock('CakeResponse'));

		$this->collection = new ComponentCollection();
		$this->collection->init($this->Controller);

		$AuthMock = $this->getMock('AuthComponent', array(), array($this->collection));
		$this->Controller->Auth = $AuthMock;

		$SessionMock = $this->getMock('SessionComponent', array(), array($this->collection));
		$this->Controller->Session = $AuthMock;

		$this->CartManager = new CartManagerComponent($this->collection);
		$this->CartManager->Auth = $AuthMock;
		$this->CartManager->Session = $SessionMock;
	}

/**
 * endTest
 *
 * @return void
 */
	public function tearDown() {
		ClassRegistry::flush();
		unset($this->CartManager);
	}

/**
 * testInitialize
 *
 * @return void
 */
	public function testInitialize() {
		$this->CartManager->initialize($this->Controller, array());

		$this->assertTrue(is_a($this->CartManager->CartModel, 'Cart'));
		$this->assertEquals($this->CartManager->sessionKey, 'Cart');
	}

/**
 * testStartup
 *
 * @return void
 */
	public function testStartup() {
		$this->collection = new ComponentCollection();
		$this->collection->init($this->Controller);

		$this->CartManager = $this->getMock('CartManagerComponent', array('captureBuy'), array($this->collection));
		$this->CartManager->initialize($this->Controller, array());
	}

/**
 * testGetBuy
 *
 * @return void
 */
	public function testGetBuy() {
		$this->CartManager->settings['getBuy'] = true;
		$this->CartManager->Controller = $this->Controller;
		$this->Controller->request->params['named'] = array(
			'item' => 'item-1',
			'model' => 'Item',
			'quantity' => 1
		);

		$this->CartManager->Controller->request->expects($this->any())
			->method('is')
			->with('get')
			->will($this->returnValue(true));

		$result = $this->CartManager->getBuy();

		$this->assertEquals($result, array(
			'CartsItem' => array(
				'model' => 'Item',
				'quantity' => 1,
				'foreign_key' => 'item-1')
			)
		);
	}

/**
 * testPostBuy
 *
 * @return void
 */
	public function testPostBuy() {
		$this->CartManager->settings['postBuy'] = true;
		$this->CartManager->Controller = $this->Controller;
		$this->Controller->request->data = array(
			'CartsItem' => array(
				'model' => 'Item',
				'quantity' => 1,
				'foreign_key' => 'item-1'
			)
		);

		$this->CartManager->Controller->request->expects($this->any())
			->method('is')
			->with('post')
			->will($this->returnValue(true));

		$result = $this->CartManager->postBuy();
		$this->assertEquals($result, array(
			'CartsItem' => array(
				'model' => 'Item',
				'quantity' => 1,
				'foreign_key' => 'item-1')
			)
		);
	}

/**
 * testRequiresShipping
 *
 * @return void
 */
	public function testRequiresShipping() {
		$this->CartManager->settings['sessionKey'] = 'Cart';

		$this->CartManager->Session->expects($this->at(0))
			->method('read')
			->with('Cart.Cart.requires_shipping')
			->will($this->returnValue(true));

		$result = $this->CartManager->requiresShipping();
		$this->assertTrue($result);

		$this->CartManager->Session->expects($this->at(0))
			->method('read')
			->with('Cart.Cart.requires_shipping')
			->will($this->returnValue(false));

		$result = $this->CartManager->requiresShipping();
		$this->assertFalse($result);
	}

/**
 * testAfterAddItemRedirect
 * 
 * @return void
 */
	public function testAfterAddItemRedirect() {
		$this->CartManager->Controller = $this->getMock('CartTestItemsController', array('redirect'));
		$this->CartManager->settings['afterAddItemRedirect'] = '/bought';

		$this->CartManager->Controller->expects($this->any())
			->method('redirect')
			->with('/bought')
			->will($this->returnValue(true));

		$this->CartManager->afterAddItemRedirect(array('name' => 'CakePHP', 'quantity' => '1'));
	}

/**
 * testCaptureBuy
 *
 * @return void
 */
	public function testCaptureBuy() {

	}

}