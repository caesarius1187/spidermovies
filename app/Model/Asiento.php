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
    //Aca vamos a guardar las cuentas relacionadas con algunos asientos especiales que vamos a autoatizar
    public $devengamientoSUSS = ['2250','2253','2254','2255','2256','2257','2345'
    ,'307','1383','1384','1419','1420','1421','1422','1423','1500','1378'];/*+Aportes + Contribuciones*/

//    110101002 Caja Efectivo
//    601061001 Ventas X1
//    210401401 IVA Devito Fiscal Gerenal
    public $devengamientoVenta = ['5','601061001','1467'];
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
