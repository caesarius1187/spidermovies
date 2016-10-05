<?php
App::uses('AppModel', 'Model');
/**
 * Estudio Model
 *
 * @property Grupocliente $Grupocliente
 * @property Tareasxclientesxestudio $Tareasxclientesxestudio
 * @property Tareasximpxestudio $Tareasximpxestudio
 * @property User $User
 */
class Estudio extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Grupocliente' => array(
			'className' => 'Grupocliente',
			'foreignKey' => 'estudio_id',
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
		'Tareasxclientesxestudio' => array(
			'className' => 'Tareasxclientesxestudio',
			'foreignKey' => 'estudio_id',
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
		/*'Tareasximpxestudio' => array(
			'className' => 'Tareasximpxestudio',
			'foreignKey' => 'estudio_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),*/
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'estudio_id',
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
