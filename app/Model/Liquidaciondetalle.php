<?php
App::uses('AppModel', 'Model');
/**
 * Liquidaciondetalle Model
 *
 * @property User $User
 * @property Lugarpago $Lugarpago
 * @property Impcli $Impcli
 * @property Archivo $Archivo
 */
class Liquidaciondetalle extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */


	// Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(		
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
		
	);

}
