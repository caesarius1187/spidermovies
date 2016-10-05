<?php
App::uses('AppModel', 'Model');
/**
 * Impcliprovincia Model
 *
 * @property Provincia $Provincia
 */
class Impcliprovincia extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'provincia_id';


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
			'order' => 'Partido.codigo'
		),
		'Localidade' => array(
			'className' => 'Localidade',
			'foreignKey' => 'localidade_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	public $hasMany = array(
		'Encuadrealicuota' => array(
			'className' => 'Encuadrealicuota',
			'foreignKey' => 'impcliprovincia_id',
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
