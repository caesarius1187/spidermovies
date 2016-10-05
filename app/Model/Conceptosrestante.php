<?php
App::uses('AppModel', 'Model');
/**
 * Conceptosrestante Model
 *
 * @property Partido $Partido
 * @property Cliente $Cliente
 * @property Impcli $Impcli
 * @property Comprobante $Comprobante
 * @property Conceptostipo $Conceptostipo
 */
class Conceptosrestante extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Localidade' => array(
			'className' => 'Localidade',
			'foreignKey' => 'localidade_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Partido' => array(
			'className' => 'Partido',
			'foreignKey' => 'partido_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
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
		),
		'Comprobante' => array(
			'className' => 'Comprobante',
			'foreignKey' => 'comprobante_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Conceptostipo' => array(
			'className' => 'Conceptostipo',
			'foreignKey' => 'conceptostipo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
