<?php
App::uses('CartsItem', 'Cart.Model');
/**
 * CartsItem Test
 * 
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class CartsItemTest extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.Cart.User',
		'plugin.Cart.Cart',
		'plugin.Cart.Item',
		'plugin.Cart.Order',
		'plugin.Cart.CartsItem',
	);

/**
 * startUp
 *
 * @return void
 */
	public function setUp() {
		$this->CartsItem = ClassRegistry::init('Cart.CartsItem');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		ClassRegistry::flush();
		unset($this->CartsItem);
	}

/**
 * testInstance
 *
 * @return void
 */
	public function testInstance() {
		$this->assertTrue(is_a($this->CartsItem, 'CartsItem'));
	}

/**
 * testValidateItem
 *
 * @return void
 */
	public function testValidateItem() {
		$data = array(
			'foo' => 'bar'
		);
		$expected = array (
			'name' => array (
				(int)0 => 'required'
			),
			'foreign_key' => array (
				(int)0 => 'required'
			),
			'model' => array (
				(int)0 => 'required'
			),
			'price' => array (
				(int)0 => 'required'
			),
			'quantity' => array (
				(int)0 => 'You must enter a quantity'
			)
		);
		$result = $this->CartsItem->validateItem($data);
		$this->assertFalse($result);
		$this->assertEquals($this->CartsItem->validationErrors, $expected);

		$result = $this->CartsItem->validateItem(array(
				'name' => 'Eizo',
				'model' => 'CartsItem',
				'foreign_key' => 'item-1',
				'quantity' => 1,
				'price' => 52.00,
			)
		);
		$this->assertTrue($result);
	}

/**
 * testMergeItems
 *
 * @return void
 */
	public function testMergeItems() {
		$data1 = array(
			'CartsItem' => array(
				0 => array(
					'foreign_key' => 'asf123',
					'model' => 'Foo')));

		$data2 = array(
			'CartsItem' => array(
				0 => array(
					'foreign_key' => 'ufsfasf123',
					'model' => 'Bar'),
				1 => array(
					'foreign_key' => 'asf123',
					'model' => 'Foo'),
				2 => array(
					'foreign_key' => '1111111',
					'model' => 'Foo'
				)
			)
		);

		$result = $this->CartsItem->mergeItems($data2, $data1);
		$this->assertEquals($result, array(
			'CartsItem' => array(
				0 => array(
					'foreign_key' => 'ufsfasf123',
					'model' => 'Bar'),
				1 => array(
					'foreign_key' => 'asf123',
					'model' => 'Foo'),
				2 => array(
					'foreign_key' => '1111111',
					'model' => 'Foo')
				)
			)
		);

		$result = $this->CartsItem->mergeItems($data1, $data2);
		$this->assertEquals($result, array(
			'CartsItem' => array(
				0 => array(
					'foreign_key' => 'asf123',
					'model' => 'Foo'),
				1 => array(
					'foreign_key' => 'ufsfasf123',
					'model' => 'Bar'),
				2 => array(
					'foreign_key' => '1111111',
					'model' => 'Foo')
				)
			)
		);
	}

/**
 * testRemoveItem
 *
 * @return void
 */
	public function testRemoveItem() {
		$data = array(
			'foreign_key' => 'item-1',
			'model' => 'Item');
		$result = $this->CartsItem->removeItem('cart-1', $data);
		$this->assertTrue($result);

		$result = $this->CartsItem->find('count', array(
			'contain' => array(),
			'conditions' => array(
				'CartsItem.foreign_key' => 'item-1',
				'CartsItem.model' => 'Item',
				'CartsItem.cart_id' => 'cart-1'
			)
		));

		$this->assertEquals($result, 0);
	}

/**
 * testAddItem
 *
 * @return void
 */
	public function testAddItem() {
		$result = $this->CartsItem->addItem('cart-1',
			array(
				'name' => 'Eizo',
				'model' => 'CartsItem',
				'foreign_key' => 'item-1',
				'quantity' => 1,
				'price' => 52.00,
			)
		);
		$this->assertTrue(is_array($result));
		$this->assertEquals($result['CartsItem']['name'], 'Eizo');
		$this->assertEquals($result['CartsItem']['model'], 'CartsItem');
		$this->assertEquals($result['CartsItem']['foreign_key'], 'item-1');
		$this->assertEquals($result['CartsItem']['quantity'], 1);
		$this->assertEquals($result['CartsItem']['price'], 52.00);
	}

}