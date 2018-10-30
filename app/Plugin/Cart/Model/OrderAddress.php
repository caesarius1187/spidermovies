<?php
App::uses('CartAppModel', 'Cart.Model');

/**
 * Order Address Model
 *
 * Thought to be used for shipping and billing addresses
 */
class OrderAddress extends CartAppModel {

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Search.Searchable'
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Order' => array(
			'className' => 'Cart.Order'
		)
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'first_name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty')),
		'last_name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty')),
		'street' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty')),
		'city' => array(
			'notEmpty' => array(
			'rule' => 'notEmpty')),
		'zip' => array(
			'notEmpty' => array(
			'rule' => 'notEmpty')),
		'country' => array(
			'notEmpty' => array(
			'rule' => 'notEmpty')),
		'type' => array(
			'notEmpty' => array(
			'rule' => 'notEmpty')
		),
	);

/**
 * Filters args for search
 *
 * @var array
 */
	public $filterArgs = array(
		array('name' => 'first_name', 'type' => 'like', 'field' => 'first_name'),
		array('name' => 'last_name', 'type' => 'like', 'field' => 'last_name'),
		array('name' => 'street', 'type' => 'like', 'field' => 'street'),
		array('name' => 'zip', 'type' => 'like', 'field' => 'zip'),
		array('name' => 'city', 'type' => 'like', 'field' => 'city'),
	);

/**
 * Gets all addresses by user id ordered by primary address first and then first
 * name ascending
 *
 * @param string $userId
 * @return array
 */
	public function byUserId($userId = null) {
		$this->find('all', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.user_id' => $userId
			),
			'order' => array(
				'primary' => 'ASC',
				'first_name' => 'ASC'
			)
		));
	}

/**
 * beforeSave callback
 *
 * @param array $options
 * @return boolean
 */
	public function beforeSave($options = array()) {
		if (!parent::beforeSave($options)) {
			return false;
		}

		$this->sanitizeFields();
		return true;
	}

/**
 * Sanitizes the address fields
 *
 * - Cleans white spaces before and after the strings
 *
 *
 * @see OrderAdress::beforeSave();
 * @return void
 */
	public function sanitizeFields() {
		foreach ($this->data[$this->alias] as $field => $value) {
			if (is_string($value)) {
				$this->data[$this->alias][$field] = trim($value);
			}
		}
	}

/**
 * Detects a duplicate address based on the first argument or if null Model::$data
 *
 * - fullReturn: Returns the full record set instead of just the id of the duplicate
 * - fields: List of table fields to compare
 *
 * @param array $data
 * @param array $options
 * @return boolean|array
 */
	public function findDuplicate($data = null, $options = array()) {
		$defaults = array(
			'fullReturn' => false,
			'fields' => array_keys($this->validate)
		);

		$options = Hash::merge($defaults, $options);

		if (empty($data)) {
			$data = $this->data;
		}

		if (empty($options['fields'])) {
			$options['fields'] = array_keys($this->validate);
		}

		$conditions = array();
		foreach ($options['fields'] as $field) {
			if (isset($data[$this->alias][$field])) {
				$conditions[$this->alias . '.' . $field][$field] = $data[$this->alias][$field];
			}
		}

		if (empty($conditions)) {
			return false;
		}

		$result = $this->find('first', array(
			'contain' => array(),
			'conditions' => $conditions
		));

		if (empty($result)) {
			return false;
		}

		if ($options['fullReturn'] === true) {
			return $result;
		}

		return $result[$this->alias][$this->primaryKey];
	}

}