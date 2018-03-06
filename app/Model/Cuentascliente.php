<?php
App::uses('AppModel', 'Model');
/**
 * Cbus Model
 *
 * @property Bancosysindicato $Bancosysindicato
 */
class Cuentascliente extends AppModel {
	public $displayField = 'nombre';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		
	);
 
//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public function altabiendeuso($cliid,$prefijo,$descripcion,$nombre){
            $Cuenta = ClassRegistry::init('Cuenta');
            //hay que buscar las cuentas que tengan el prefijo y que se llamen como el parametro descripcion
            //la primera que no tenga cuenta cliente la activamos, reemplazando XX por el nombre
            $optionCuentas=[
                'contain'=>[
                    'Cuentascliente'=>[
                        'conditions'=>[
                            'Cuentascliente.cliente_id'=>$cliid
                        ]
                    ]
                ],
                'conditions'=>[
                    'Cuenta.numero LIKE "'.$prefijo.'%"',
                    'Cuenta.nombre'=>$descripcion,
                ]
            ];
            $cuentas = $Cuenta->find('all',$optionCuentas);
            $respuesta=[];
            $respuesta['respuesta']="";
            $encontreCuentas = false;
            foreach ($cuentas as $micuenta) {
                if(count($micuenta['Cuentascliente'])==0){
                    //podemos usar esta cuenta
                    $this->create();
                    $nombreCuentaClie = str_replace("XX", $nombre, $descripcion);
                    $this->set('cliente_id',$cliid);
                    $this->set('cuenta_id',$micuenta['Cuenta']['id']);
                    $this->set('nombre',$nombreCuentaClie);
                    if ($this->save()){
                        $respuesta['respuesta'].="Se dio de alta la cuenta del Bien de Uso: ".$nombreCuentaClie."</br>";
                        $respuesta['cuenta_id']=$this->getLastInsertID();;
                    }else{
                        $respuesta['respuesta'].="Error al guardar: NO se dio de alta la cuenta del Bien de Uso: ".$nombreCuentaClie."</br>";
                    }
                    $encontreCuentas = true;
                    return $respuesta;
                    break;
                }
            }
            if(!$encontreCuentas){
                $respuesta['respuesta'].="Error No hay cuentas disponibles para "
                        . "registrar el bien de uso en el plan de cuentas. "
                        . "Comuniquiese con el administrador del sistema</br>";
                $respuesta['cuenta_id']=0;
                return $respuesta;
            }
	}
	public $belongsTo = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
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
		)
	);
	

	public $hasMany = array(
		'Saldocuentacliente' => array(
			'className' => 'Saldocuentacliente',
			'foreignKey' => 'cuentascliente_id',
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
		'Bienespersonale' => array(
			'className' => 'Bienespersonale',
			'foreignKey' => 'cuentascliente_id',
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
		'Movimiento' => array(
			'className' => 'Movimiento',
			'foreignKey' => 'cuentascliente_id',
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
		'Movimientosbancario' => array(
			'className' => 'Movimientosbancario',
			'foreignKey' => 'cuentascliente_id',
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
		'Cuentaclientevalororigen' => array(
			'className' => 'Bienesdeuso',
			'foreignKey' => 'cuentaclientevalororigen_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cuentaclienteactualizacion' => array(
			'className' => 'Bienesdeuso',
			'foreignKey' => 'cuentaclienteactualizacion_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cuentaclienteterreno' => array(
			'className' => 'Bienesdeuso',
			'foreignKey' => 'cuentaclienteterreno_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cuentaclienteedificacion' => array(
			'className' => 'Bienesdeuso',
			'foreignKey' => 'cuentaclienteedificacion_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cuentaclientemejora' => array(
			'className' => 'Bienesdeuso',
			'foreignKey' => 'cuentaclientemejora_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cbu' => array(
			'className' => 'Cbu',
			'foreignKey' => 'cuentascliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
