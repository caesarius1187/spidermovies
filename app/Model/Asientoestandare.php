<?php
App::uses('AppModel', 'Model');
/**
 * Asiento Model
 *
 */
class Asientoestandare extends AppModel {
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Impuesto' => array(
            'className' => 'Impuesto',
            'foreignKey' => 'impuesto_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Cuenta' => array(
            'className' => 'Cuenta',
            'foreignKey' => 'cuenta_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),

    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        /*'Movimiento' => array(
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
        )*/
    );

}
