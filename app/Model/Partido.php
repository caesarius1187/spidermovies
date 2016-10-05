<?php
App::uses('AppModel', 'Model');
/**
 * Partido Model
 *
 * @property Cliente $Cliente
 * @property Localidade $Localidade
 */
class Partido extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'codname';

	
	var $virtualFields = array(
	    'codname' => 'CONCAT(Partido.codigo, "-", Partido.nombre)'
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'partido_id',
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
		'Localidade' => array(
			'className' => 'Localidade',
			'foreignKey' => 'partido_id',
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
		'Conceptosrestante' => array(
			'className' => 'Conceptosrestante',
			'foreignKey' => 'partido_id',
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
