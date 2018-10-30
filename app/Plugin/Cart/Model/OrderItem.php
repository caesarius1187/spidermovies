<?php
App::uses('CartAppModel', 'Cart.Model');

/**
 * OrderItem Model
 *
 * @author Florian Krämer
 * @copyright 2012 - 2014 Florian Krämer
 * @license MIT
 */
class OrderItem extends CartAppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Order' => array(
			'className' => 'Cart.Order',
			'counterCache' => true
		)
	);

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
