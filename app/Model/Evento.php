<?php
App::uses('AppModel', 'Model');
/**
 * Evento Model
 *
 * @property User $User
 * @property Lugarpago $Lugarpago
 * @property Impcli $Impcli
 * @property Archivo $Archivo
 */
class Evento extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'periodo';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Lugarpago' => array(
			'className' => 'Lugarpago',
			'foreignKey' => 'lugarpago_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Impcli' => array(
			'className' => 'Impcli',
			'foreignKey' => 'impcli_id',
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
		'Archivo' => array(
			'className' => 'Archivo',
			'foreignKey' => 'evento_id',
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
