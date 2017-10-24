<?php
App::uses('AppModel', 'Model');
/**
 * Modelo Model
 *
 * @property Marca $Marca
 */
class Modelo extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	/*public $displayField = 'nombre';*/
	public $displayField = 'codname';

		
		var $virtualFields = array(
		    'codname' => 'CONCAT(Modelo.nombre, " ", Modelo.tiponombre)'
		);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Marca' => array(
			'className' => 'Marca',
			'foreignKey' => 'marca_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
