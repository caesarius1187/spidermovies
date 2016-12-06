<?php
App::uses('AppModel', 'Model');
/**
 * Movimientosbancario Model
 *
 * @property Bancosysindicato $Bancosysindicato
 */
class Movimientosbancario extends AppModel {

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
	
	public $belongsTo = array(
		'Impcli' => array(
			'className' => 'Impcli',
			'foreignKey' => 'impcli_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cbu' => array(
			'className' => 'Cbu',
			'foreignKey' => 'cbu_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cuentascliente' => array(
			'className' => 'Cuentascliente',
			'foreignKey' => 'cuentascliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/*
	public $hasMany = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Cuenta' => array(
			'className' => 'Cuenta',
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
	*/
}
