<?php
App::uses('AppModel', 'Model');
/**
 * Eventosimpuesto Model
 *
 * @property User $User
 * @property Lugarpago $Lugarpago
 * @property Impcli $Impcli
 * @property Archivo $Archivo
 */
class Eventosimpuesto extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */


	// Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Lugarpago' => array(
			'className' => 'Lugarpago',
			'foreignKey' => 'lugarpago_id',
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
		'Partido' => array(
			'className' => 'Partido',
			'foreignKey' => 'partido_id',
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
		'Archivo' => array(
			'className' => 'Archivo',
			'foreignKey' => 'eventosimpuesto_id',
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
		'Basesprorrateada' => array(
			'className' => 'Basesprorrateada',
			'foreignKey' => 'eventosimpuesto_id',
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
		'Usosaldo' => array(
			'className' => 'Usosaldo',
			'foreignKey' => 'eventosimpuesto_id',
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
