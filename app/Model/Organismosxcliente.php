<?php
App::uses('AppModel', 'Model');
/**
 * Organismosxcliente Model
 *
 * @property Cliente $Cliente
 */
class Organismosxcliente extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'tipoorganismo';


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
}
