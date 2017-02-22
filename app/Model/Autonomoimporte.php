<?php
App::uses('AppModel', 'Model');
/**
 * Autonomoimporte Model
 *
 */
class Autonomoimporte extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
    public $belongsTo = [
        'Autonomocategoria' => [
            'className' => 'Autonomocategoria',
            'foreignKey' => 'autonomocategoria_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ],
    ];
}
