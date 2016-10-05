<?php
App::uses('AppModel', 'Model');
/**
 * Tareasxclientesxestudio Model
 *
 * @property Tareascliente $Tareascliente
 * @property Estudio $Estudio
 * @property User $User
 */
class Tareasxclientesxestudio extends AppModel {

	public $order = array("Tareasxclientesxestudio.orden" => "asc");

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'descripcion';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Tareascliente' => array(
			'className' => 'Tareascliente',
			'foreignKey' => 'tareascliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Estudio' => array(
			'className' => 'Estudio',
			'foreignKey' => 'estudio_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function isOwnedBy($post, $user) {
	    return $this->field('id', array('id' => $post, 'user_id' => $user)) !== false;
	}

}
