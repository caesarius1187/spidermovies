<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * User Model
 *
 * @property Estudio $Estudio
 * @property Evento $Evento
 */
class UserBK extends AppModel {
	public $displayField = 'nombre';

/**
 * Validation rules
 *
 * @var array
 */
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
			
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
               
	);

    public function beforeSave($options = array()) {
        // hash our password
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return parent::beforeSave($options);
    }

}
