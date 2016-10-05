<?php
App::uses('AppModel', 'Model');
/**
 * Tareasximpxestudio Model
 *
 * @property Tareasimpuesto $Tareasimpuesto
 * @property Estudio $Estudio
 */
class Tareasximpxestudio extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'tareasimpuesto_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'estudio_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Tareasimpuesto' => array(
			'className' => 'Tareasimpuesto',
			'foreignKey' => 'tareasimpuesto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Estudio' => array(
			'className' => 'Estudio',
			'foreignKey' => 'estudio_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
