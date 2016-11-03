<?php
App::uses('AppController', 'Controller');
/**
 * Eventosimpuestos Controller
 *
 * @property Asiento $Asiento
 * @property PaginatorComponent $Paginator
 */
class AsientosController extends AppController {
/**
 * Components
 *
 * @var array
 */
    //este array es el que se va a usar para relacionar las cuentas con los impuestos
    //no se si tendriamos que guardarlo en una tabla pero por ahora va a servir

	public $components = array('Paginator');
	public function contabilizarimpuesto($impcli = null, $periodo = null){
        $this->loadModel('Impcli');
        $this->loadModel('Cuentascliente');
        $options = array(
            'contain'=>[],
            'conditions' => array(
                'Impcli.' . $this->Impcli->primaryKey => $impcli,
            ),
        );
        $myCli = $this->Impcli->find('first', $options);
        $options = array(
            'contain'=>array(
                'Impuesto'=>[
                    'Asientoestandare'=>[
                        'Cuenta'=>[
                            'Cuentascliente'=>[
                                'conditions'=>[
                                    'Cuentascliente.cliente_id'=>$myCli['Impcli']['cliente_id']
                                ]
                            ]
                        ]
                    ]
                ]
            ),
            'conditions' => array(
                'Impcli.' . $this->Impcli->primaryKey => $impcli,
            ),
        );
        $impCli = $this->Impcli->find('first', $options);
        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentas=false;
        foreach ($impCli['Impuesto']['Asientoestandare'] as $asientoestandar) {
            if(count($asientoestandar['Cuenta']['Cuentascliente'])==0){
                //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                $this->Cuentascliente->create();
                $this->Cuentascliente->set('cliente_id',$myCli['Impcli']['cliente_id']);
                $this->Cuentascliente->set('cuenta_id',$asientoestandar['Cuenta']['id']);
                $this->Cuentascliente->save();
                $secrearoncuentas=true;
            }
        }
        //ahora ya estamos seguros de que las cuentas clientes existen
        if($secrearoncuentas){
            $impCli = $this->Impcli->find('first', $options);
        }
        $this->set(compact('impCli'));
        //ahora vamos a listar las cuentas clientes activadas y pero con el nombre de la cuenta
        $cuentaclienteOptions = array(
            'conditions' => array(
                'Cuentascliente.cliente_id'=> $myCli['Impcli']['cliente_id']
            ),
            'fields'=>array('Cuentascliente.id','Cuenta.nombre'),
            'joins'=>array(
                array('table'=>'impuestos',
                    'alias' => 'Impuesto',
                    'type'=>'inner',
                    'conditions'=> array(
                        'Impcli.impuesto_id = Impuesto.id',
                        'AND'=>array(
                            'Impuesto.organismo <> "sindicato"',
                            'Impuesto.organismo <> "banco"'
                        )
                    )
                ),
                array('table'=>'periodosactivos',
                    'alias' => 'Periodosactivo',
                    'type'=>'inner',
                    'conditions'=> array(
                        $conditionsImpCliHabilitadosImpuestos
                    )
                ),
            ),

        );
        $impclis=$this->Impcli->find('list',$clienteImpuestosOptions);

    }

}
