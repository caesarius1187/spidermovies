<?php
App::uses('CartAppModel', 'Cart.Model');
App::uses('AddressType', 'Cart.Model');

/**
 * Order Model
 *
 * @author Florian Krämer
 * @copyright 2014 Florian Krämer
 * @license MIT
 */
class Order extends CartAppModel {

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Search.Searchable'
	);

/**
 * Order status
 *
 * @var array
 */
	public $orderStatuses = array(
		'pending',
		'failed',
		'completed',
		'refunded',
		'partial-refunded'
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Cart' => array(
			'className' => 'Cart.Cart'
		),
		'User' => array(
			'className' => 'User'
		),
		'BillingAddress' => array(
			'className' => 'Cart.OrderAddress',
		),
		'ShippingAddress' => array(
			'className' => 'Cart.OrderAddress',
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'OrderItem' => array(
			'className' => 'Cart.OrderItem'
		)
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'total' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'This must be a number'
			)
		),
		'status' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'The order requires a status'
			)
		),
		'currency' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'You must select a currency'
			)
		),
		'cart_snapshot' => array(
			'notEmpty' => array(
				'rule' => array('isArray', true),
				'message' => 'You must add the cart data to the order'
			)
		)
	);

/**
 * Filters args for search
 *
 * @var array
 */
	public $filterArgs = array(
		array('name' => 'username', 'type' => 'like', 'field' => 'User.username'),
		array('name' => 'email', 'type' => 'like', 'field' => 'User.email'),
		array('name' => 'invoice_number', 'type' => 'like'),
		array('name' => 'total', 'type' => 'value'),
		array('name' => 'created', 'type' => 'like'),
	);

/**
 * beforeSave callback
 *
 * @param  array $options, not used
 * @return boolean
 */
	public function beforeSave($options = array()) {
		$this->_getOrderRecordBeforeSave();
		$this->data = $this->_serializeFields(
			array(
				'cart_snapshot',
				'additional_data',
				'shipping_address',
				'billing_address'
			),
			$this->data
		);
		return true;
	}

/**
 * Serializes the cart snapshot data
 *
 * This method is intended to be called only inside Order::beforeSave()
 *
 * @return void
 */
	protected function _serializeCartSnapshot() {
		if (!empty($this->data[$this->alias]['cart_snapshot']) && is_array($this->data[$this->alias]['cart_snapshot'])) {
			$this->data[$this->alias]['cart_snapshot'] = serialize($this->data[$this->alias]['cart_snapshot']);
		}
	}

/**
 * Gets the unchanged order data
 *
 * This method is intended to be called only inside Order::beforeSave()
 *
 * @return void
 */
	protected function _getOrderRecordBeforeSave() {
		if (!empty($this->data[$this->alias][$this->primaryKey])) {
			$this->orderRecordBeforeSave = $this->find('first', array(
				'contain' => array(),
				'conditions' => array(
					$this->alias . '.' . $this->primaryKey => $this->data[$this->alias][$this->primaryKey]
				)
			));
		}
	}

/**
 * Compares changes to the order model fields of the just saved record with the
 * Order::orderRecordBeforeSave and triggers an event if any field was changed
 *
 * This method is intended to be called only inside Order::afterSave()
 *
 * @return void
 */
	protected function _detectOrderChange() {
		if (!empty($this->orderRecordBeforeSave)) {
			$changedFields = array();
			foreach ($this->data[$this->alias] as $field => $value) {
				if (isset($this->orderRecordBeforeSave[$this->alias][$field]) && $this->orderRecordBeforeSave[$this->alias][$field] !== $value) {
					$changedFields[] = $value;
				}
			}

			if (!empty($changedFields)) {
				$this->getEventManager()->dispatch(new CakeEvent('Order.changed', $this, array(
					$this->data,
					$this->orderRecordBeforeSave,
					$changedFields
				)));
			}
		}
	}

/**
 * afterSave callback
 *
 * @param  boolean $created
 * @param  array $options
 * @return void
 */
	public function afterSave($created, $options = array()) {
		if ($created) {
			if (empty($this->data[$this->alias]['currency'])) {
				$this->data[$this->alias]['currency'] = Configure::read('Cart.defaultCurrency');
			}

			$this->data[$this->alias]['order_number'] = $this->orderNumber($this->data);
			$this->data[$this->alias]['invoice_number'] = $this->invoiceNumber($this->data);
			$this->data[$this->alias][$this->primaryKey] = $this->getLastInsertId();

			$result = $this->save($this->data, array(
				'validate' => false,
				'callbacks' => false
			));

			$this->data = $result;
			$this->getEventManager()->dispatch(new CakeEvent('Order.created', $this, array($this->data)));
		}

		$this->_detectOrderChange();
		$this->orderRecordBeforeSave = null;
	}

/**
 * afterFind callback
 *
 * @param  array $results
 * @param  bool $primary, not used
 * @return array
 */
	public function afterFind($results, $primary = false) {
		$results = $this->unserializeFields($results);
		return $results;
	}

/**
 * Unserializes the data in the cart_snapshot field when it is present
 *
 * @param    array $results
 * @internal param array $results
 * @return   array modified results array
 */
	public function unserializeFields($results) {
		if (!empty($results)) {
			foreach ($results as $key => $result) {
				$results[$key] = $this->_unserializeFields(array('cart_snapshot'), $result);
			}
		}
		return $results;
	}

/**
 * Returns the data for a user to view an order he made
 *
 * @param string $orderId Order UUID
 * @param array $options
 * @throws NotFoundException
 * @return array
 */
	public function view($orderId = null, $options = array()) {
		$defaults = array(
			'contain' => array(
				'OrderItem',
				'BillingAddress',
				'ShippingAddress'
			),
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $orderId
			)
		);

		$order = $this->find('first', Hash::merge($defaults, $options));

		if (empty($order)) {
			throw new NotFoundException(__d('cart', 'The order does not exist.'));
		}
		return $order;
	}

/**
 * Returns the data for an order for the admin
 *
 * @param  string $orderId Order UUID
 * @return array
 * @throws NotFoundException
 */
	public function adminView($orderId = null) {
		$order = $this->find('first', array(
			'contain' => array(
				'User',
				'OrderItem'
			),
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $orderId)
			)
		);

		if (empty($order)) {
			throw new NotFoundException(__d('cart', 'The order does not exist.'));
		}
		return $order;
	}

/**
 * beforeCreateOrder
 *
 * @param array $data
 * @return array
 */
	public function beforeCreateOrder($data) {
		$defaults = array(
			'cart_id' => empty($data['Cart']['id']) ? null : $data['Cart']['id'],
			'user_id' => empty($data['Cart']['user_id']) ? null : $data['Cart']['user_id'],
			'status' => empty($data[$this->alias]['status']) ? 'pending' : $data[$this->alias]['status'],
			'cart_snapshot' => $data,
			'total' => $data['Cart']['total'],
		);

		$data[$this->alias] = Hash::merge($data[$this->alias], $defaults);

		unset($data[$this->alias][$this->primaryKey]);
		return $data;
	}

/**
 * Turns the cart into an order
 *
 * - validates all data for the order first
 * - starts a DB transaction
 * - saves all data
 * - if no exception thrown commits the DB transaction, else rolls it back
 *
 * @throws Exception
 * @param array $data Post data
 * @param array $options
 * @return boolean
 */
	public function createOrder($data, $options = array()) {
		$data = $this->beforeCreateOrder($data);

		$Event = new CakeEvent(
			'Order.beforeCreateOrder',
			$this,
			array(
				'order' => $data
			)
		);
		$this->getEventManager()->dispatch($Event);
		if ($Event->result === false) {
			return false;
		}

		if ($this->beforeOrderValidation($data)) {
			$DataSource = $this->getDataSource();
			$DataSource->begin();

			try {
				$data = $this->saveAddresses($data);

				$saveOptions = array(
					'validate' => false,
					'callbacks' => true,
				);

				$this->create();
				$this->save($data, Hash::merge($options, $saveOptions));
				$data[$this->alias][$this->primaryKey] = $orderId = $this->getLastInsertId();

				$data = $this->saveItems($orderId, $data);
			} catch (Exception $e) {
				$DataSource->rollback();
				throw $e;
			}

			$DataSource->commit();

			$Event = new CakeEvent(
				'Order.created',
				$this, array(
					'order' => $data
				)
			);
			$this->getEventManager()->dispatch($Event);
			$this->data = $data;
			return true;
		}

		return false;
	}

/**
 * Generates an invoice number
 *
 * @param  array  $data Order data
 * @param  string $date
 * @return string
 */
	public function invoiceNumber($data = array(), $date = null) {
		$Event = new CakeEvent(
			'Order.createInvoiceNumber',
			$this,
			array(
				$data
			)
		);
		$this->getEventManager()->dispatch($Event);
		if ($Event->isStopped()) {
			return $Event->result;
		}

		if (empty($date)) {
			$date = date('Y-m-d');
		}

		$count = $this->find('count', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.created LIKE' => substr($date, 0, -2) . '%'
			)
		));

		if ($count == 1) {
			$increment = $count;
		} else {
			$increment = $count + 1;
		}

		return str_replace('-', '', $date) . '-' . $increment;
	}

/**
 * Order number
 *
 * @param $data, not currently used
 * @return string
 */
	public function orderNumber($data = array()) {
		return $this->find('count');
	}

/**
 * Checks the order data if the shipping address is the same as the billing address
 *
 * @param array $data
 * @param string $field Field in the ShippingAddress data set to be used for the check
 * @return boolean
 */
	public function shippingIsSameAsBilling($data, $field = 'same_as_billing') {
		if (isset($data['ShippingAddress'][$field])) {
			return (bool)$data['ShippingAddress'][$field];
		}
		return false;
	}

/**
 * Validates the shipping and billing address
 *
 * @param array $data The order data with the shipping and billing address
 * @return boolean
 */
	public function validateAddresses($data) {
		if (empty($data['BillingAddress']) && empty($data['ShippingAddress'])) {
			return true;
		}
		$this->BillingAddress->set($data);
		$validBillingAddress = $this->BillingAddress->validates($data);

		$sameAsBilling = $this->shippingIsSameAsBilling($data);

		if ($sameAsBilling === true) {
			$validShippingAddress = true;
		} else {
			$this->ShippingAddress->set($data);
			$validShippingAddress = $this->ShippingAddress->validates($data);
		}

		return ($validBillingAddress && $validShippingAddress);
	}

/**
 * saveAddresses
 *
 * @param $data
 * @return array
 */
	public function saveAddresses($data) {
		$billingAddressId = $this->BillingAddress->findDuplicate($data);
		if ($billingAddressId === false) {
			$data['BillingAddress']['type'] = AddressType::BILLING;
			$this->BillingAddress->create();
			$this->BillingAddress->save($data, array('validate' => false));
			$billingAddressId = $this->BillingAddress->getLastInsertId();
		}

		$sameAsBilling = $this->shippingIsSameAsBilling($data);
		if ($sameAsBilling === false) {
			$shippingAddressId = $this->ShippingAddress->findDuplicate($data);
			if ($shippingAddressId === false) {
				$data['ShippingAddress']['type'] = AddressType::SHIPPING;
				$this->ShippingAddress->create();
				$this->ShippingAddress->save($data, array('validate' => false));
				$shippingAddressId = $this->ShippingAddress->getLastInsertId();
			}
		} else {
			$shippingAddressId = $billingAddressId;
			$data['ShippingAddress'] = $data['BillingAddress'];
		}

		$data[$this->alias]['shipping_address_id'] = $shippingAddressId;
		$data[$this->alias]['billing_address_id'] = $billingAddressId;

		return $data;
	}

/**
 * Saves all the items for the order in the persistent order_items table
 *
 * @param $orderId
 * @param array $data
 * @return void
 */
	public function saveItems($orderId, $data) {
		foreach ($data['CartsItem'] as $item) {
			$item['order_id'] = $orderId;
			$this->OrderItem->create();
			$result = $this->OrderItem->save(array(
				'OrderItem' => $item
			));
			$result['OrderItem']['id'] = $this->OrderItem->getLastInsertId();
			if (is_string($result['OrderItem']['additional_data'])) {
				$result['OrderItem']['additional_data'] = unserialize($result['OrderItem']['additional_data']);
			}
			$data['OrderItem'][] = $result['OrderItem'];
		}
		return $data;
	}

/**
 * This method validates all data, the order and all associated data
 *
 * All associated data is validates as well and not skipped on the first
 * false result to get the errors displayed in the form.
 *
 * @param array $data
 * @param array $options
 * @return boolean
 */
	public function beforeOrderValidation($data, $options = array()) {
		$this->set($data);
		$validOrder = $this->validates();
		$validAddresses = $this->validateAddresses($data);
		return ($validOrder && $validAddresses);
	}

}
