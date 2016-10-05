<?php
App::uses('AppModel', 'Model');
/**
 * Puntosdeventa Model
 *
 * @property Cliente $Cliente
 * @property Domicilio $Domicilio
 * @property Venta $Venta
 */
class Puntosdeventa extends AppModel {

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
		'Venta' => array(
			'className' => 'Venta',
			'foreignKey' => 'puntosdeventa_id',
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
