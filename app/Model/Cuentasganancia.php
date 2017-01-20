<?php
App::uses('AppModel', 'Model');
/**
 * Actividade Model
 *
 * @property Cuentasganancia $Cuentasganancia
 */
class Cuentasganancia extends AppModel {

/**
 * Display field
 *
 * @var string
 */
//	public $displayField = 'codname';
//	var $virtualFields = array(
//	    'codname' => 'CONCAT(Actividade.descripcion, " ", Actividade.nombre)'
//	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Actividadcliente' => array(
			'className' => 'Actividadcliente',
			'foreignKey' => 'actividadcliente_id',
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
		),
	);
//	public $hasMany = array(
//		'Alicuota' => array(
//			'className' => 'Alicuota',
//			'foreignKey' => 'actividade_id',
//			'dependent' => false,
//			'conditions' => '',
//			'fields' => '',
//			'order' => '',
//			'limit' => '',
//			'offset' => '',
//			'exclusive' => '',
//			'finderQuery' => '',
//			'counterQuery' => ''
//		),
//	);
		
}
