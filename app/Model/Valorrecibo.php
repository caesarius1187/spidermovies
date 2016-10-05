<?php
App::uses('AppModel', 'Model');
/**
 * Valorrecibo Model
 *
 * @property Cctxconcepto $Cctxconcepto
 * @property Empleado $Empleado
 */
class Valorrecibo extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Cctxconcepto' => array(
			'className' => 'Cctxconcepto',
			'foreignKey' => 'cctxconcepto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Empleado' => array(
			'className' => 'Empleado',
			'foreignKey' => 'empleado_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
