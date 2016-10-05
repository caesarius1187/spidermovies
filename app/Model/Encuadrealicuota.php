<?php
App::uses('AppModel', 'Model');
/**
 * Encuadrealicuota Model
 *
 * @property Actividadcliente $Actividadcliente
 * @property Impcliprovincia $Impcliprovincia
 */
class Encuadrealicuota extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'concepto';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Actividadcliente' => array(
			'className' => 'Actividadcliente',
			'foreignKey' => 'actividadcliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Impcliprovincia' => array(
			'className' => 'Impcliprovincia',
			'foreignKey' => 'impcliprovincia_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
