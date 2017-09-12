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
	public function altaBienDeUso($cliid,$prefijo,$descripcion,$nombre){
        $Cuenta = ClassRegistry::init('Cuenta');
        //hay que buscar las cuentas que tengan el prefijo y que se llamen como el parametro descripcion
        //la primera que no tenga cuenta cliente la activamos, reemplazando XX por el nombre
        $optionCuentas=[
            'contain'=>[
                'Cuentascliente'=>[
                    'contitions'=>[
                        'Cuentascliente.cliente_id'=>$cliid
                    ]
                ]
            ],
            'conditions'=>[
                'Cuenta.numero LIKE "'+$prefijo+'%"',
                'Cuenta.nombre'=>$descripcion,
            ]
        ];
        $cuentas = $Cuenta->find('all',$optionCuentas);
        $respuesta=[];
        $respuesta['respuesta']="";
        foreach ($cuentas as $cuenta) {
            if(count($cuenta['Cuentascliente'])==0){
                //podemos usar esta cuenta
                $this->Cuentascliente->create();
                $nombreCuentaClie = str_replace("XX", $nombre, $descripcion);
                $this->Cuentascliente->set('cliente_id',$this->request->data['Impcli']['cliente_id']);
                $this->Cuentascliente->set('cuenta_id',$cuenta['Cuenta']['id']);
                $this->Cuentascliente->set('nombre',$nombreCuentaClie);
                if ($this->Cuentascliente->save()){
                    $respuesta['respuesta'].="Se dio de alta la cuenta del Bien de Uso: ".$nombreCuentaClie."</br>";
                    $respuesta['cuenta_id'].=$this->Cuentascliente->getLastInsertID();;
                }else{
                    $respuesta['respuesta'].="NO se dio de alta la cuenta del Bien de Uso: ".$nombreCuentaClie."</br>";
                }
                break;
            }
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
		)
	);
}
