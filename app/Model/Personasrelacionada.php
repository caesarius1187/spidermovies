<?php
App::uses('AppModel', 'Model');
/**
 * Personasrelacionada Model
 *
 * @property Cliente $Cliente
 * @property Localidade $Localidade
 */
class Personasrelacionada extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombrefantasia';


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
		),
		'Localidade' => array(
			'className' => 'Localidade',
			'foreignKey' => 'localidade_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
