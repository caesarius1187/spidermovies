<?php
App::uses('AppModel', 'Model');
/**
 * Conveniocolectivotrabajo Model
 *
 * @property Impcli $Impcli
 * @property Cctxconcepto $Cctxconcepto
 */
class Conveniocolectivotrabajo extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $displayField = 'nombre';

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

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Cctxconcepto' => array(
			'className' => 'Cctxconcepto',
			'foreignKey' => 'conveniocolectivotrabajo_id',
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
		'Empleado' => array(
			'className' => 'Empleado',
			'foreignKey' => 'conveniocolectivotrabajo_id',
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
		'Cargo' => array(
			'className' => 'Cargo',
			'foreignKey' => 'conveniocolectivotrabajo_id',
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
