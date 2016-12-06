<?php
App::uses('AppModel', 'Model');
/**
 * Cbu Model
 *
 */
class Cbu extends AppModel {
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $displayField = 'cbu';
    public $belongsTo = [
        'Impcli' => [
            'className' => 'Impcli',
            'foreignKey' => 'impcli_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ],
        'Cuentascliente' => [
            'className' => 'Cuentascliente',
            'foreignKey' => 'cuentascliente_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ],
    ];

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Movimientosbancario' => array(
			'className' => 'Movimientosbancario',
			'foreignKey' => 'cbu_id',
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
