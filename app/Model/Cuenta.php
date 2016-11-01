<?php
App::uses('AppModel', 'Model');
/**
 * Cbus Model
 *
 * @property Bancosysindicato $Bancosysindicato
 */
class Cuenta extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		
	);

//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	
	public $hasMany = array(
		'Cuentascliente' => array(
			'className' => 'Cuentascliente',
			'foreignKey' => 'cuenta_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)		
	);

}
