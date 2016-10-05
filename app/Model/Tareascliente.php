<?php
App::uses('AppModel', 'Model');
/**
 * Tareascliente Model
 *
 * @property Tareasxclientesxestudio $Tareasxclientesxestudio
 */
class Tareascliente extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Tareasxclientesxestudio' => array(
			'className' => 'Tareasxclientesxestudio',
			'foreignKey' => 'tareascliente_id',
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
