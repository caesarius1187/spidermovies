<?php
App::uses('AppModel', 'Model');
/**
 * Periodosactivo Model
 *
 * @property Impcli $Impcli
 */
class Periodosactivo extends AppModel {


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
