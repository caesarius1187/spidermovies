<?php
App::uses('AppModel', 'Model');
/**
 * Cctxconcepto Model
 *
 * @property Conveniocolectivotrabajo $Conveniocolectivotrabajo
 * @property Concepto $Concepto
 * @property Valorrecibo $Valorrecibo
 */
class Cctxconcepto extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Conveniocolectivotrabajo' => array(
			'className' => 'Conveniocolectivotrabajo',
			'foreignKey' => 'conveniocolectivotrabajo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Concepto' => array(
			'className' => 'Concepto',
			'foreignKey' => 'concepto_id',
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
		'Valorrecibo' => array(
			'className' => 'Valorrecibo',
			'foreignKey' => 'cctxconcepto_id',
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
