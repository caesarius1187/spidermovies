<?php
App::uses('CartAppModel', 'Cart.Model');
/**
 *
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class ShippingMethod extends CartAppModel {

/**
 * Display field name
 *
 * @var    string
 * @access public
 */
	public $displayField = 'name';

/**
 * Constructor
 *
 * @param  mixed $id Model ID
 * @param  string $table Table name
 * @param  string $ds Datasource
 * @access public
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->validate = array(
		);
	}

/**
 * Adds a new record to the database
 *
 * @param  array post data, should be Contoller->data
 * @throws OutOfBoundsException
 * @return array
 * @access public
 */
	public function add($data = null) {
		if (!empty($data)) {
			$this->create();
			$result = $this->save($data);
			if ($result !== false) {
				$this->data = array_merge($data, $result);
				return true;
			} else {
				throw new OutOfBoundsException(__d('cart', 'Could not save the shippingMethod, please check your inputs.', true));
			}
			return $return;
		}
	}

/**
 * Edits an existing Shipping Method.
 *
 * @param  string $id, shipping method id
 * @param  array $data, controller post data usually $this->data
 * @return mixed True on successfully save else post data as array
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function edit($id = null, $data = null) {
		$shippingMethod = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id,
				)));

		if (empty($shippingMethod)) {
			throw new OutOfBoundsException(__d('cart', 'Invalid Shipping Method', true));
		}
		$this->set($shippingMethod);

		if (!empty($data)) {
			$this->set($data);
			$result = $this->save(null, true);
			if ($result) {
				$this->data = $result;
				return true;
			} else {
				return $data;
			}
		} else {
			return $shippingMethod;
		}
	}

/**
 * Returns the record of a Shipping Method.
 *
 * @param  string $id, shipping method id.
 * @return array
 * @throws OutOfBoundsException If the element does not exists
 * @access public
 */
	public function view($id = null) {
		$shippingMethod = $this->find('first', array(
			'conditions' => array(
				"{$this->alias}.{$this->primaryKey}" => $id)));

		if (empty($shippingMethod)) {
			throw new OutOfBoundsException(__d('cart', 'Invalid Shipping Method', true));
		}

		return $shippingMethod;
	}

/**
 * Validates the deletion
 *
 * @param  string $id, shipping method id
 * @param  array $data, controller post data usually $this->data
 * @throws OutOfBoundsException If the element does not exists
 * @throws Exception
 * @return boolean True on success
 * @access public
 */
	public function validateAndDelete($id = null, $data = array()) {
		$shippingMethod = $this->find('first', array(
			'conditions' => array(
					$this->alias . '.' . $this->primaryKey => $id,
				)
			)
		);

		if (empty($shippingMethod)) {
			throw new OutOfBoundsException(__d('cart', 'Invalid Shipping Method', true));
		}

		$this->data['shippingMethod'] = $shippingMethod;
		if (!empty($data)) {
			$data['ShippingMethod']['id'] = $id;
			$tmp = $this->validate;
			$this->validate = array(
				'id' => array('rule' => 'notEmpty'),
				'confirm' => array('rule' => '[1]'));

			$this->set($data);
			if ($this->validates()) {
				if ($this->delete($data['ShippingMethod']['id'])) {
					return true;
				}
			}
			$this->validate = $tmp;
			throw new Exception(__d('cart', 'You need to confirm to delete this Shipping Method', true));
		}
	}

}
