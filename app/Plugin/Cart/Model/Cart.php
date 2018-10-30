<?php
App::uses('CartAppModel', 'Cart.Model');
App::uses('CakeEvent', 'Event');
App::uses('CakeEventManager', 'Event');
/**
 * Cart Model
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 *
 * @property CartsItem CartsItem
 */
class Cart extends CartAppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'CartsItem' => array(
			'className' => 'Cart.CartsItem'
		)
	);

/**
 * Validation domain for translations
 *
 * @var string
 */
	public $validationDomain = 'cart';

/**
 * Validation parameters
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true, 'allowEmpty' => false,
				'message' => 'Please enter a name for your cart.'
			)
		),
		'user_id' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true, 'allowEmpty' => false,
				'message' => 'You have to add a user id.'
			)
		)
	);

/**
 * Checks if a cart is active or not
 *
 * @param string cart uuid
 * @return boolean
 */
	public function isActive($cartId = null) {
		if (empty($cartId)) {
			$cartId = $this->id;
		}

		$result = $this->find('count', array(
			'conditions' => array(
				$this->alias . '.active' => 1,
				$this->alias . '.' . $this->primaryKey => $cartId)
			)
		);

		return ($result != 0);
	}

/**
 * Returns the active cart for a user, if there is no one it will create one and return it
 *
 * @param string user uuid
 * @param boolean $create
 * @return array
 */
	public function getActive($userId = null, $create = true) {
		$result = $this->find('first', array(
			'contain' => array(
				'CartsItem'
			),
			'conditions' => array(
				$this->alias . '.active' => 1,
				$this->alias . '.user_id' => $userId
			)
		));

		if (!empty($result)) {
			return $result;
		}

		if (!$create) {
			return false;
		}

		$this->create();
		$result = $this->save(array(
			$this->alias => array(
				'user_id' => $userId,
				'active' => 1,
				'name' => __d('cart', 'My cart'))
			)
		);

		$result[$this->alias]['id'] = $this->getLastInsertId();
		return $result;
	}

/**
 * Sets a cart as active cart in the case there multiple carts present for an user
 *
 * @throws NotFoundException
 * @param string $cartId
 * @param string $userId
 * @return boolean
 */
	public function setActive($cartId, $userId = null) {
		$result = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $cartId,
				$this->alias . '.user_id' => $userId
			),
			'fields' => array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.active',
				$this->alias . '.user_id',
			)
		));

		if (empty($result)) {
			throw new NotFoundException(__d('cart', 'Invalid cart!'));
		}

		if ($result[$this->alias]['active'] == 1) {
			return false;
		}

		$this->updateAll(
			array('active' => 0),
			array('user_id' => $userId)
		);

		if ($this->saveField('active', 1)) {
			return true;
		};
		return false;
	}

/**
 * Returns a cart and its contents
 *
 * @throws NotFoundException
 * @param string $cartId
 * @param string $userId
 * @return array
 */
	public function view($cartId = null, $userId = null) {
		$conditions = array(
			$this->alias . '.' . $this->primaryKey => $cartId
		);

		if (!empty($userId)) {
			$conditions[$this->alias . '.user_id'] = $userId;
		}

		$result = $this->find('first', array(
			'contain' => array(
				'CartsItem'
			),
			'conditions' => $conditions
		));

		if (empty($result) && empty($userId)) {
			throw new NotFoundException(__d('cart', 'Cart not found!'));
		}

		return $result;
	}

/**
 * Adds and updates an item if it already exists in the cart
 *
 * Passing this through to the join table model
 *
 * @param string $cartId
 * @param array $itemData
 * @return boolean
 */
	public function addItem($cartId, $itemData) {
		return $this->CartsItem->addItem($cartId, $itemData);
	}

/**
 * Called from the CartManagerComponent when an item is removed from the cart
 *
 * Passing this through to the join table model
 *
 * @param string $cartId Cart UUID
 * @param $itemData
 * @return boolean
 */
	public function removeItem($cartId, $itemData) {
		return $this->CartsItem->removeItem($cartId, $itemData);
	}

/**
 * Drops the cart an all it's items
 *
 * @param string $cartId
 * @return boolean
 */
	public function emptyCart($cartId) {
		return $this->delete($cartId);
	}

/**
 * Checks if one of the items in the cart is not flagged as a virtual item and 
 * requires by this shipping.
 *
 * Virtual means that it can be a download or a service or whatever else.
 *
 * @param array $cartItems Array of items in the cart
 * @return boolean
 */
	public function requiresShipping($cartItems = array()) {
		if (!empty($cartItems)) {
			foreach ($cartItems as $cartKey => $cartItem) {
				if (!isset($cartItem['virtual']) || isset($cartItem['virtual']) && $cartItem['virtual'] == 0) {
					return true;
				}
			}
		}
		return false;
	}

/**
 * Calculates the totals of a cart
 *
 * @param array $data
 * @param array $options
 * @return array
 */
	public function calculateCart($data, $options = array()) {
		$Event = new CakeEvent('Cart.beforeCalculateCart', $this, array('cartData' => $data));
		$this->getEventManager()->dispatch($Event);
		if ($Event->isStopped()) {
			return false;
		}

		if (!empty($Event->result)) {
			$data = $Event->result;
		}

		if (isset($data['CartsItem'])) {
			$data[$this->alias]['item_count'] = count($data['CartsItem']);
		} else {
			$data[$this->alias]['total'] = 0.00;
			return $data;
		}

		$cart[$this->alias]['requires_shipping'] = $this->requiresShipping($data['CartsItem']);

		$data = $this->applyDiscounts($data);
		$data = $this->applyTaxRules($data);
		$data = $this->calculateTotals($data);

		$Event = new CakeEvent('Cart.afterCalculateCart', $this, array('cartData' => $data));
		$this->getEventManager()->dispatch($Event);
		if (!empty($Event->result)) {
			$data = $Event->result;
		}

		if (isset($data[$this->alias][$this->primaryKey])) {
			$this->save($data, array(
				'validate' => false
			));
		}

		return $data;
	}

/**
 * Applies tax rules to the cart
 *
 * @param array $cartData
 * @return array
 */
	public function applyTaxRules($cartData) {
		$Event = new CakeEvent('Cart.applyTaxRules', $this, array(
			'cartData' => $cartData
		));
		$this->getEventManager()->dispatch($Event);
		if (!empty($Event->result)) {
			return $Event->result;
		}
		return $cartData;
	}

/**
 * Applies discount rules to the cart
 *
 * @param array $cartData
 * @return array
 */
	public function applyDiscounts($cartData) {
		$Event = new CakeEvent('Cart.applyDiscounts', $this, array(
			'cartData' => $cartData
		));
		$this->getEventManager()->dispatch($Event);
		if (!empty($Event->result)) {
			return $Event->result;
		}
		return $cartData;
	}

/**
 * Calculates the total amount of the cart
 *
 * @param array $cartData
 * @return array
 */
	public function calculateTotals($cartData) {
		$Event = new CakeEvent('Cart.calculateTotals', $this, array(
			'cartData' => $cartData
		));
		$this->getEventManager()->dispatch($Event);
		if (!empty($Event->result)) {
			return $Event->result;
		}
		return $cartData;
	}

/**
 * Merges an array of item data with the cart items in the database.
 *
 * This method needs to be called during the login process before the redirect
 * happens but after the user was authenticated if you want to take the items
 * from the non-logged in user into the users database cart.
 *
 * @param integer|string $cartId
 * @param array $cartItems
 * @return integer Number of merged items
 */
	public function mergeItems($cartId, $cartItems) {
		$dbItems = $this->CartsItem->find('all', array(
			'contain' => array(),
			'conditions' => array(
				'CartsItem.cart_id' => $cartId
			)
		));

		$mergedItems = 0;
		foreach ($cartItems as $cartKey => $cartItem) {
			$matched = false;
			foreach ($dbItems as $dbItem) {
				if ($dbItem['CartsItem']['model'] == $cartItem['model'] && $dbItem['CartsItem']['foreign_key'] == $cartItem['foreign_key']) {
					$this->CartsItem->save(array_merge($dbItem['CartsItem'], $cartItem));
					$matched = true;
					break;
				}
			}
			if ($matched === false) {
				$mergedItems++;
				$this->addItem($cartId, $cartItem);
			}
		}
		return $mergedItems;
	}

/**
 * Add a new cart
 *
 * @param array $postData
 * @param $userId
 * @return boolean
 */
	public function add($postData, $userId = null) {
		if (!empty($userId)) {
			$postData[$this->alias]['user_id'] = $userId;
		}

		$this->create();
		$result = $this->save($postData);
		if ($result) {
			$cartId = $this->getLastInsertID();
			$result[$this->alias][$this->primaryKey] = $cartId;
			if (isset($postData[$this->alias]['active']) && $postData[$this->alias]['active'] == 1) {
				$this->setActive($cartId, $userId);
			}
			return true;
		}
		return false;
	}

/**
 * Checkout confirmation
 *
 * @param array $data
 * @return boolean
 */
	public function confirmCheckout($data) {
		return (isset($data[$this->alias]['confirm_checkout']) && $data[$this->alias]['confirm_checkout'] == 1);
	}

/**
 * afterFind callback
 *
 * @param array
 * @param boolean
 * @return array
 */
	public function afterFind($results, $primary = true) {
		foreach ($results as &$result) {
			$result = $this->_unserializeFields(array('additional_data'), $result);
		}
		return $results;
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