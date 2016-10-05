<?php
App::uses('AppModel', 'Model');
/**
 * Vencimiento Model
 *
 * @property Impuesto $Impuesto
 */
class Vencimiento extends AppModel {

//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Impuesto' => array(
			'className' => 'Impuesto',
			'foreignKey' => 'impuesto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
