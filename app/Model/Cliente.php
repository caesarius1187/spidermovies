<?php
App::uses('AppModel', 'Model');
/**
 * Cliente Model
 *
 * @property Grupocliente $Grupocliente
 * @property Partido $Partido
 * @property Localidad $Localidade
 * @property Deposito $Deposito
 * @property Eventoscliente $Eventoscliente
 * @property Honorario $Honorario
 * @property Impcli $Impcli
 * @property Organismosxcliente $Organismosxcliente
 */
class Cliente extends AppModel {

	 public $displayField = 'nombre';

/**
 * Display field
 *
 * @var string
 */
	


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Grupocliente' => array(
			'className' => 'Grupocliente',
			'foreignKey' => 'grupocliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
		'Localidade' => array(
			'className' => 'Localidade',
			'foreignKey' => 'localidade_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Deposito' => array(
			'className' => 'Deposito',
			'foreignKey' => 'cliente_id',
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
		'Eventoscliente' => array(
			'className' => 'Eventoscliente',
			'foreignKey' => 'cliente_id',
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
		'Honorario' => array(
			'className' => 'Honorario',
			'foreignKey' => 'cliente_id',
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
		'Impcli' => array(
			'className' => 'Impcli',
			'foreignKey' => 'cliente_id',
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
		'Organismosxcliente' => array(
			'className' => 'Organismosxcliente',
			'foreignKey' => 'cliente_id',
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
		'Domicilio' => array(
			'className' => 'Domicilio',
			'foreignKey' => 'cliente_id',
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
		'Personasrelacionada' => array(
			'className' => 'Personasrelacionada',
			'foreignKey' => 'cliente_id',
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
		'Venta' => array(
			'className' => 'Venta',
			'foreignKey' => 'cliente_id',
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
		'Compra' => array(
			'className' => 'Compra',
			'foreignKey' => 'cliente_id',
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
		'Sueldo' => array(
			'className' => 'Sueldo',
			'foreignKey' => 'cliente_id',
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
		'Conceptosrestante' => array(
			'className' => 'Conceptosrestante',
			'foreignKey' => 'cliente_id',
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
		'Puntosdeventa' => array(
			'className' => 'Puntosdeventa',
			'foreignKey' => 'cliente_id',
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
		'Subcliente' => array(
			'className' => 'Subcliente',
			'foreignKey' => 'cliente_id',
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
		'Empleado' => array(
			'className' => 'Empleado',
			'foreignKey' => 'cliente_id',
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
		'Provedore' => array(
			'className' => 'Provedore',
			'foreignKey' => 'cliente_id',
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
		'Actividadcliente' => array(
			'className' => 'Actividadcliente',
			'foreignKey' => 'cliente_id',
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
		'Plandepago' => array(
			'className' => 'Plandepago',
			'foreignKey' => 'cliente_id',
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
		'Cuentascliente' => array(
			'className' => 'Cuentascliente',
			'foreignKey' => 'cliente_id',
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
		'Asiento' => array(
			'className' => 'Asiento',
			'foreignKey' => 'cliente_id',
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
		'Bienesdeuso' => array(
			'className' => 'Bienesdeuso',
			'foreignKey' => 'cliente_id',
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
    public function impuestosActivados($cliid,$periodo){
        $pemes = substr($periodo, 0, 2);
        $peanio = substr($periodo, 3);
        //A: Es menor que periodo Hasta
        $esMenorQueHasta = array(
            //HASTA es mayor que el periodo
            'OR'=>array(
                'SUBSTRING(Periodosactivo.hasta,4,7)*1 > '.$peanio.'*1',
                'AND'=>array(
                    'SUBSTRING(Periodosactivo.hasta,4,7)*1 >= '.$peanio.'*1',
                    'SUBSTRING(Periodosactivo.hasta,1,2) >= '.$pemes.'*1'
                ),
            )
        );
        //B: Es mayor que periodo Desde
        $esMayorQueDesde = array(
            'OR'=>array(
                'SUBSTRING(Periodosactivo.desde,4,7)*1 < '.$peanio.'*1',
                'AND'=>array(
                    'SUBSTRING(Periodosactivo.desde,4,7)*1 <= '.$peanio.'*1',
                    'SUBSTRING(Periodosactivo.desde,1,2) <= '.$pemes.'*1'
                ),
            )
        );
        //C: Tiene Periodo Hasta 0 NULL
        $periodoNull = array(
            'OR'=>array(
                array('Periodosactivo.hasta'=>null),
                array('Periodosactivo.hasta'=>""),
            )
        );
        $conditionsImpCliHabilitados = array(
            //El periodo esta dentro de un desde hasta
            'AND'=> array(
                $esMayorQueDesde,
                'OR'=> array(
                    $esMenorQueHasta,
                    $periodoNull
                )
            )
        );
        $cliente= $this->find('first', array(
                'contain'=>array(
                    'Empleado'=>[],
                    'Impcli'=>[
                        'Impuesto',
                        'Periodosactivo'=>[
                            'conditions'=>$conditionsImpCliHabilitados
                        ]
                    ]
                ),
                'conditions' => array(
                    'id' => $cliid,
                ),
            )
        );
        $impuestosactivos = [];
        $impuestosactivos['tienebanco'] = false;
        $impuestosactivos['contabiliza'] = false;
        $impuestosactivos['tieneEmpleados'] = false;
        $impuestosactivos['ganancias'] = false;
        $impuestosactivos['monotributo'] = false;
        foreach ($cliente['Impcli'] as $impcli) {
            if(Count($impcli['Periodosactivo'])!=0){
                if( $impcli['Impuesto']['organismo']=='banco'){
                    $impuestosactivos['tienebanco']=true;
                }
                $impuestosactivos[$impcli['impuesto_id']]=true;
                if( in_array($impcli['Impuesto']['id'], ['19','5','28','160'])){
                    $impuestosactivos['contabiliza']=true;
                }
				if( in_array($impcli['Impuesto']['id'], ['5','28','160'])){
                    $impuestosactivos['ganancias']=true;
                }
				if( in_array($impcli['Impuesto']['id'], ['4'])){
                    $impuestosactivos['monotributo']=true;
                }
            }else{
                $impuestosactivos[$impcli['impuesto_id']]=false;
            }

        }
        if (Count($cliente['Empleado'])!=0) {
            $impuestosactivos['tieneEmpleados'] = true;
        }
        return $impuestosactivos;
    }
}
