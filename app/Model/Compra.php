<?php
App::uses('AppModel', 'Model');
/**
 * Compra Model
 *
 * @property Cliente $Cliente
 * @property Puntosdeventa $Puntosdeventa
 * @property Subcliente $Subcliente
 * @property Localidade $Localidade
 */
class Compra extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'numerocomprobante';


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
		'Provedore' => array(
			'className' => 'Provedore',
			'foreignKey' => 'provedore_id',
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
		'Comprobante' => array(
			'className' => 'Comprobante',
			'foreignKey' => 'comprobante_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Tipogasto' => array(
			'className' => 'Tipogasto',
			'foreignKey' => 'tipogasto_id',
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
	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Bienesdeuso' => array(
			'className' => 'Bienesdeuso',
			'foreignKey' => 'compra_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
}
