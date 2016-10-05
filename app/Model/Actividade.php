<?php
App::uses('AppModel', 'Model');
/**
 * Actividade Model
 *
 * @property Actividadcliente $Actividadcliente
 */
class Actividade extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'codname';

	
	var $virtualFields = array(
	    'codname' => 'CONCAT(Actividade.descripcion, " ", Actividade.nombre)'
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
		'Alicuota' => array(
			'className' => 'Alicuota',
			'foreignKey' => 'actividade_id',
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
		'Actividadcliente' => array(
			'className' => 'Actividadcliente',
			'foreignKey' => 'actividade_id',
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
