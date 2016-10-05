<?php
App::uses('AppModel', 'Model');
/**
 * Provedore Model
 *
 * @property Cliente $Cliente
 */
class Provedore extends AppModel {
	public $displayField = 'dnicuit';
	var $virtualFields = array(
		'dnicuit' => 'CONCAT(Provedore.nombre, " ",Provedore.dni, " ", Provedore.cuit)'
	);
/**
 * Display field
 *
 * @var string
 */
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	public $hasMany = array(
		'Compra' => array(
			'className' => 'Compra',
			'foreignKey' => 'provedore_id',
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
