<?php
App::uses('AppModel', 'Model');
/**
 * Comprobante Model
 *
 * @property clientesxcomprobante $Clientesxcomprobante
 */
class Comprobante extends AppModel {

	public $displayField = 'codname';


	var $virtualFields = array(
		'codname' => 'CONCAT(Comprobante.codigo, "-", Comprobante.nombre)'
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Venta' => array(
			'className' => 'Venta',
			'foreignKey' => 'comprobante_id',
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
