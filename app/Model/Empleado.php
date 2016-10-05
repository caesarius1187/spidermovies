<?php
App::uses('AppModel', 'Model');
/**
 * Empleado Model
 *
 * @property Cliente $Cliente
 * @property Valorrecibo $Valorrecibo
 */
class Empleado extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre';


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
		'Conveniocolectivotrabajo' => array(
			'className' => 'Conveniocolectivotrabajo',
			'foreignKey' => 'conveniocolectivotrabajo_id',
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
		'Domicilio' => array(
			'className' => 'Domicilio',
			'foreignKey' => 'domicilio_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Valorrecibo' => array(
			'className' => 'Valorrecibo',
			'foreignKey' => 'empleado_id',
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
