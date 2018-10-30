<?php
App::uses('BuyableBehavior', 'Cart.Model/Behavior');
App::uses('Model', 'Model');
/**
 * CartTestItemModel
 */
class CartTestItemModel extends Model {

	public $useTable = 'items';
	public $alias = 'Item';

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Cart.Buyable' => array());
}

/**
 * CartsItem Test
 *
 * @property mixed Model
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class BuyableBehaviorTest extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Cart.Cart',
		'plugin.Cart.Item',
		'plugin.Cart.Order',
		'plugin.Cart.CartsItem');

/**
 * startUp
 *
 * @return void
 */
	public function setUp() {
		$this->Model = ClassRegistry::init('CartTestItemModel');
		$this->Model->alias = 'Item';
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		ClassRegistry::flush();
		unset($this->Model);
	}

/**
 * 
 */
	public function testBindCartModel() {
		$this->Model->bindCartModel();
	}

}