<?php
App::uses('AppModel', 'Model');
/**
 * Venta Model
 *
 * @property Cliente $Cliente
 */
class Venta extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	/*public $validate = array(
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
		
	);*/

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
		),
		'Subcliente' => array(
			'className' => 'Subcliente',
			'foreignKey' => 'subcliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Actividadcliente' => array(
			'className' => 'Actividadcliente',
			'foreignKey' => 'actividadcliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Puntosdeventa' => array(
			'className' => 'Puntosdeventa',
			'foreignKey' => 'puntosdeventa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Localidade' => array(
			'className' => 'Localidade',
			'foreignKey' => 'localidade_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Comprobante' => array(
			'className' => 'Comprobante',
			'foreignKey' => 'comprobante_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Tipogasto' => array(
			'className' => 'Tipogasto',
			'foreignKey' => 'tipogasto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	public $hasMany = array(
		'Bienesdeuso' => array(
			'className' => 'Bienesdeuso',
			'foreignKey' => 'venta_id',
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
