<?php
App::uses('AppModel', 'Model');
/**
 *
 */
class Anexogasto extends AppModel {

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
		'Cuentascliente' => array(
			'className' => 'Cuentascliente',
			'foreignKey' => 'cuentascliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	public $hasMany = array(

	);
}
