<?php
App::uses('AppModel', 'Model');
/**
 * Usosaldo Model
 *
 * @property Cliente $Cliente
 */
class Usosaldo extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	/*public $validate = array(
		'cliente_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		
	);*/

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Conceptosrestante' => array(
			'className' => 'Conceptosrestante',
			'foreignKey' => 'conceptosrestante_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Eventosimpuesto' => array(
			'className' => 'Eventosimpuesto',
			'foreignKey' => 'eventosimpuesto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
	public $hasMany = array(
		
	);

}
