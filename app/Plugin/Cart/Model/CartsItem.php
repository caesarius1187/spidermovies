<?php
App::uses('CartAppModel', 'Cart.Model');
/**
 * Carts Item Model
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class CartsItem extends CartAppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Cart' => array(
			'className' => 'Cart.Cart',
			'counterCache' => 'item_count'
		)
	);

/**
 * Validation parameters
 *
 * @var array
 */
	public $validate = array(
		'cart_id' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true,
				'allowEmpty' => false
			)
		),
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true,
				'allowEmpty' => false
			)
		),
		'foreign_key' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true,
				'allowEmpty' => false
			)
		),
		'model' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true,
				'allowEmpty' => false
			)
		),
		'price' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true,
				'allowEmpty' => false
			)
		),
		'quantity' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true,
				'allowEmpty' => false,
				'message' => 'You must enter a quantity'
			),
			'naturalNumber' => array(
				'rule' => array('naturalNumber'),
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Must be a natural number'
			)
		)
	);

/**
 * Validates an item record set
 *
 * @param array $data
 * @param boolean $loggedIn
 * @return void
 */
	public function validateItem($data, $loggedIn = false) {
		if ($loggedIn === false) {
			unset($this->validate['cart_id']);
		}
		$this->set($data);
		return $this->validates();
	}

/**
 * Adds and updates an item if it already exists in the cart
 *
 * @param string $cartId
 * @param array $itemData
 * @param array $options
 * @throws InvalidArgumentException
 * @return mixed
 */
	public function addItem($cartId, $itemData, $options = array()) {
		$defaults = array(
			'validates' => true
		);
		$options = Hash::merge($defaults, $options);

		if (isset($itemData[$this->alias])) {
			$itemData = $itemData[$this->alias];
		}

		if (!isset($itemData['foreign_key']) || !isset($itemData['model'])) {
			throw new InvalidArgumentException(__d('cart', 'foreign_key or model is missing from the item data!'));
		}

		$item = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				'cart_id' => $cartId,
				'model' => $itemData['model'],
				'foreign_key' => $itemData['foreign_key']
			)
		));

		if (empty($item)) {
			$item = array($this->alias => $itemData);
			$item[$this->alias]['cart_id'] = $cartId;
			$this->create();
		} else {
			$item[$this->alias] = Hash::merge($item[$this->alias], $itemData);
		}

		return $this->save($item, array(
			'validates' => $options['validates']
		));
	}

/**
 * Called from the CartManagerComponent when an item is removed from the cart
 *
 * @throws InvalidArgumentException
 * @param string $cartId Cart UUID
 * @param $itemData
 * @return boolean
 */
	public function removeItem($cartId, $itemData) {
		if (!isset($itemData['foreign_key']) || !isset($itemData['model'])) {
			throw new InvalidArgumentException(__d('cart', 'foreign_key or model is missing from the item data!'));
		}

		$item = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.cart_id' => $cartId,
				$this->alias . '.model' => $itemData['model'],
				$this->alias . '.foreign_key' => $itemData['foreign_key']
			)
		));

		if (empty($item)) {
			return false;
		}

		return $this->delete($item[$this->alias]['id']);
	}

/**
 * moveItem
 *
 * @todo finish it
 * @return void
 */
	public function moveItem($fromCartId, $itemData, $toCartId) {
		$item = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.cart_id' => $fromCartId,
				$this->alias . '.model' => $itemData['model'],
				$this->alias . '.foreign_key' => $itemData['foreign_key']
			)
		));
	}

/**
 * Add
 *
 * @param array $data
 * @return void
 */
	public function add($data) {
		$result = $this->find('first', array(
			'conditions' => array(
				'cart_id' => $data[$this->alias]['cart_id'],
				'foreign_key' => $data[$this->alias]['foreign_key']
			)
		));

		if (empty($result)) {

		}

		$data = array($this->alias => array($data[$this->alias]));
		foreach ($data as $item) {
			$this->create();
			$this->save($item);
		}
	}

/**
 * Merges two item arrays, used to merge cookie/sesssion/database cart item arrays to synchronize them
 *
 * This method just merges the arrays it does NOT write them to the database
 *
 * @param array $array1 The array the 2nd array gets merged into
 * @param array $array2 The array that will get merged into $array1 and override its item if present
 * @return array
 */
	public function mergeItems($array1, $array2) {
		if (!isset($array1[$this->alias])) {
			$array1[$this->alias] = array();
		}

		if (!isset($array2[$this->alias])) {
			$array2[$this->alias] = array();
		}

		foreach ($array1[$this->alias] as $key1 => $item1) {
			foreach ($array2[$this->alias] as $key2 => $item2) {
				if ($item2['foreign_key'] == $item1['foreign_key'] && $item2['model'] == $item1['model']) {
					$array1[$this->alias][$key1] = $item2;
					unset($array2[$this->alias][$key2]);
					break;
				}
			}
		}

		if (!empty($array2)) {
			foreach ($array2[$this->alias] as $key => $item) {
				$array1[$this->alias][] = $item;
			}
		}

		return $array1;
	}

/**
 * beforeSave callback
 *
 * @param  array $options, not used
 * @return boolean
 */
	public function beforeSave($options = array()) {
		$this->data = $this->_serializeFields(
			array(
				'additional_data',
			),
			$this->data
		);
		return true;
	}

}