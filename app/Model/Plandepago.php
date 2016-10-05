<?php
App::uses('AppModel', 'Model');
/**
 * Plandepago Model
 *
 * @property Impcli $Impcli
 * @property User $User
 */
class Plandepago extends AppModel {

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
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
