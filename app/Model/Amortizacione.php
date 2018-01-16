<?php
App::uses('AppModel', 'Model');
/**
 * Amortizacione Model
 *
 * @property Compra $Compra
 * @property Localidade $Localidade
 */
class Amortizacione extends AppModel {

/*' ', 	//The Associations below have been created with all possible keys, those that are not needed can be removed
        
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Bienesdeuso' => array(
			'className' => 'Bienesdeuso',
			'foreignKey' => 'bienesdeuso_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
