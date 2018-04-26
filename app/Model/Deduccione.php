<?php
App::uses('AppModel', 'Model');
/**
 * Deduccione Model
 *
 * @property Cliente $Cliente
 * @property Localidade $Localidade
 */
class Deduccione extends AppModel {

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
		'Impcli' => array(
			'className' => 'Impcli',
			'foreignKey' => 'impcli_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}