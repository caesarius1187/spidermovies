<?php
App::uses('AppModel', 'Model');
/**
 * Basesprorrateada Model
 *
 * @property Eventosimpuesto $Eventosimpuesto
 * @property Impcliprovincia $Impcliprovincia
 * @property Actividadcliente $Actividadcliente
 * @property Impcli $Impcli
 */
class Basesprorrateada extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Eventosimpuesto' => array(
			'className' => 'Eventosimpuesto',
			'foreignKey' => 'eventosimpuesto_id',
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
		),
		'Actividadcliente' => array(
			'className' => 'Actividadcliente',
			'foreignKey' => 'actividadcliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Impcli' => array(
			'className' => 'Impcli',
			'foreignKey' => 'impcli_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
