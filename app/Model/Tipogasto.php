<?php
App::uses('AppModel', 'Model');
/**
 * Tipogasto Model
 *
 * @property Compra $Compra
 */
class Tipogasto extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre';
	public $ingresosBienDeUso = [
		'50',/*Bien de uso primera*/
		'53',/*Bien de uso segunda*/
		'55',/*Venta Bien de uso tercera*/
		'62',/*Bien de uso tercera otros*/
		'66',/*Bien de uso cuarta*/
	];

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Compra' => array(
			'className' => 'Compra',
			'foreignKey' => 'tipogasto_id',
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
