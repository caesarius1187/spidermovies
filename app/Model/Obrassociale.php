<?php
App::uses('AppModel', 'Model');
/**
 * Obrassociale Model
 *
 * @property Empleado $Empleado
 */
class Obrassociale extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
         public $displayField = 'codname';

	
	var $virtualFields = array(
	    'codname' => 'CONCAT(Obrassociale.codigo, "-", Obrassociale.nombre)'
	);

	public $hasMany = array(
		'Empleado' => array(
			'className' => 'Empleado',
			'foreignKey' => 'obrassociale_id',
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
