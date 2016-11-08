<?php
App::uses('AppModel', 'Model');
/**
 * Asiento Model
 *
 */
class Asiento extends AppModel {
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
        'Movimiento' => array(
            'className' => 'Movimiento',
            'foreignKey' => 'asiento_id',
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
