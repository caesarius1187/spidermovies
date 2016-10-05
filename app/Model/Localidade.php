<?php
App::uses('AppModel', 'Model');
/**
 * Localidade Model
 *
 * @property Partido $Partido
 */
class Localidade extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre';

	
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Partido' => array(
			'className' => 'Partido',
			'foreignKey' => 'partido_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $hasMany = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'localidade_id',
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
		'Venta' => array(
			'className' => 'Venta',
			'foreignKey' => 'localidade_id',
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
		'Eventosimpuesto' => array(
			'className' => 'Eventosimpuesto',
			'foreignKey' => 'localidade_id',
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
	);
}
