<?php
App::uses('AppModel', 'Model');
/**
 * Puntosdeventa Model
 *
 * @property Cliente $Cliente
 */
class Subcliente extends AppModel {
	
	public $displayField = 'dnicuit';

	
	var $virtualFields = array(
	    'dnicuit' => 'CONCAT(Subcliente.nombre, " ",Subcliente.dni, " ", Subcliente.cuit)'
	);
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'cliente_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	public $hasMany = array(
		'Venta' => array(
			'className' => 'Venta',
			'foreignKey' => 'subcliente_id',
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
