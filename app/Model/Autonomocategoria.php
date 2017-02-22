<?php
App::uses('AppModel', 'Model');
/**
 * Autonomocategoria Model
 *
 */
class Autonomocategoria extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
    public $displayField = 'codname';


    var $virtualFields = array(
        'codname' => 'CONCAT(Autonomocategoria.rubro, " ", Autonomocategoria.tipo, " ", Autonomocategoria.categoria, " ", Autonomocategoria.codigo)'
    );
   
    public $hasMany = array(
        'Autonomoimporte' => array(
            'className' => 'Autonomoimporte',
            'foreignKey' => 'autonomocategoria_id',
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
        'Impclis' => array(
            'className' => 'Impclis',
            'foreignKey' => 'autonomocategoria_id',
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
    );
}
