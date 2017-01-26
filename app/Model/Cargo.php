<?php
App::uses('AppModel', 'Model');
/**
 * Cargo Model
 *
 */
class Cargo extends AppModel {

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
		'Conveniocolectivotrabajo' => array(
			'className' => 'Conveniocolectivotrabajo',
			'foreignKey' => 'conveniocolectivotrabajo_id',
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
		'Empleado' => array(
			'className' => 'Empleado',
			'foreignKey' => 'cargo_id',
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
