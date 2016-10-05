<?php
App::uses('AppModel', 'Model');
/**
 * Concepto Model
 *
 * @property Cctxconcepto $Cctxconcepto
 */
class Concepto extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Cctxconcepto' => array(
			'className' => 'Cctxconcepto',
			'foreignKey' => 'concepto_id',
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
