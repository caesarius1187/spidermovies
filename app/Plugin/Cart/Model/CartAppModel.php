<?php
App::uses('AppModel', 'Model');
/**
 * Cart App Model
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class CartAppModel extends AppModel {

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Containable'
	);

/**
 * Validation domain for translations
 *
 * @var string
 */
	public $validationDomain = 'cart';

/**
 * Constructor
 *
 * @param mixed $id Model ID
 * @param string $table Table name
 * @param string $ds Datasource
 * @access public
 */
	public function __construct($id = false, $table = null, $ds = null) {
		$this->_configureAssociations();
		parent::__construct($id, $table, $ds);
	}

/**
 * This is a workaround to let you define other models in the associations of
 * the cart plugin without touching it's model code.
 *
 * You could for example extend the Order model with AppOrder and then configure
 * the plugin to use that model by Configure::write('Cart.models.Order', 'AppOrder');
 *
 * @return void
 */
	protected function _configureAssociations() {
		$models = Configure::read('Cart.models');
		if (!empty($models)) {
			$types = array('belongsTo', 'hasMany', 'hasOne', 'hasAndBelongsToMany');
			foreach ($models as $model => $modelAssoc) {
				foreach ($types as $type) {
					if (isset($this->{$type}[$model])) {
						if (is_string($modelAssoc)) {
							$modelAssoc = array('className' => $modelAssoc);
						}
						$fields = array_keys($modelAssoc);
						foreach ($fields as $field) {
							$this->{$type}[$model][$field] = $modelAssoc[$field];
						}
					}
				}
			}
		}
	}

	protected function _serializeFields($fields = array(), $data = null) {
		if (empty($fields)) {
			return $data;
		}
		if (empty($data)) {
			$data = $this->data;
		}

		foreach ($fields as $field) {
			if (!empty($data[$this->alias][$field])) {
				if (!is_array($data[$this->alias][$field])) {
					$serializedData = serialize(array());
				} else {
					$serializedData = serialize($data[$this->alias][$field]);
				}
				$data[$this->alias][$field] = $serializedData;
			}
		}
		return $data;
	}

	protected function _unserializeFields($fields = array(), $data = null) {
		if (empty($fields)) {
			return $data;
		}
		if (empty($data)) {
			$data = $this->data;
		}
		foreach ($fields as $field) {
			if (isset($data[$this->alias][$field])) {
				$data[$this->alias][$field] = unserialize($data[$this->alias][$field]);
			}
		}
		return $data;
	}

/**
 * Validation method to check if the content of a field is an array
 *
 * @param mixed $check
 * @param boolean $notEmpty
 * @return boolean
 */
	public function isArray($check, $notEmpty = true) {
		$value = array_values($check);
		$value = $value[0];
		if (is_array($value)) {
			if ($notEmpty === true) {
				return (!empty($value));
			}
			return true;
		}
		return false;
	}

/**
 * Attempts to unserialize a string if it cant, it will return the original value
 *
 * @param mixed $value
 * @return mixed
 */
	public function unserializeValue($value) {
		if (is_string($value)) {
			$result = @unserialize($value);
			if ($result !== false) {
				return $result;
			}
		}
		return $value;
	}

}