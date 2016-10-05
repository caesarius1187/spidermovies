<?php
App::uses('AppModel', 'Model');
/**
 * Alicuota Model
 *
 * @property Partido $Partido
 * @property Actividade $Actividade
 */
class Alicuota extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'alicuota';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
		),
		'Actividade' => array(
			'className' => 'Actividade',
			'foreignKey' => 'actividade_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
