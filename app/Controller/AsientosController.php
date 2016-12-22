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
    public function add() {
        $this->loadModel('Movimiento');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Cuenta');
        if ($this->request->is('post')) {
            $this->Asiento->create();
            $respuesta = array('respuesta'=>'');

            $this->request->data('Asiento.0.fecha',date('Y-m-d',strtotime($this->request->data['Asiento'][0]['fecha'])));
            $respuesta['data']=$this->request->data;
            if ($this->Asiento->saveAll($this->request->data['Asiento'])) {
                $respuesta['respuesta'] = "El Asiento se guardo correctamente.</br>";
                $asientoid=0;
                if($this->request->data['Asiento'][0]['id']==0){
                    $asientoid = $this->Asiento->getLastInsertID();
                }else{
                    $asientoid = $this->request->data['Asiento'][0]['id'];
                }
                foreach ($this->request->data['Asiento'][0]['Movimiento'] as $k => $movimiento){
                    $movimiento['fecha']= date('Y-m-d',strtotime($movimiento['fecha']));
                    $movimiento['asiento_id'] = $asientoid;
                    //aca vamos a controlar que el asiento apunte a una cuenta cliente
                    //y si no apunta vamos a preguntar si hay una cuenta disponible para crear una cuenta cliente
                    // y utilizarla automaticamente para este movimiento dando de alta la cuenta para el cliente

                    if($movimiento['cuentascliente_id']==0)
                    {
                        if(isset($movimiento['cuenta_id']))
                        {
                            $optionsCuenta = [
                                'contain'=>[],
                                'conditions' => ['Cuenta.id' => $movimiento['cuenta_id']],
                                'fields'=> ['Cuenta.nombre']
                            ];
                            $CuentaDesc = $this->Cuenta->find('first', $optionsCuenta);

                            $this->Cuentascliente->create();
                            $this->Cuentascliente->set('cliente_id',$this->request->data['Asiento'][0]['cliente_id']);
                            $this->Cuentascliente->set('cuenta_id',$movimiento['cuenta_id']);
                            $this->Cuentascliente->set('nombre',$CuentaDesc['Cuenta']['nombre']);
                            if ($this->Cuentascliente->save())
                            {
                                $movimiento['cuentascliente_id'] = $this->Cuentascliente->getLastInsertID();
                                $respuesta['respuesta'].='Cuenta activada correctamente.</br>';
                            }
                            else
                            {
                                $respuesta['respuesta'].='Error al guardar cuenta. Por favor intente nuevamente.</br>';
                            }
                        }
                    }

                    if ($this->Movimiento->saveAll($movimiento)) {
                        $respuesta['respuesta'].= "El Movimiento se guardo correctamente.</br>";
                    } else {
                        $respuesta['respuesta'].="El Movimiento NO se guardo correctamente. Por favor intentelo de nuevo.</br>";
                    }
                }
            }else {
                $respuesta['respuesta']="El Asiento NO se guardo correctamente. Por favor intentelo de nuevo.</br>";
            }
        }
        $this->set('data',$respuesta);
        $this->autoRender=false;
        $this->layout = 'ajax';
        $this->render('serializejson');
        return;
    }
	public function contabilizarimpuesto($impcliid = null, $periodo = null){
        $this->loadModel('Impcli');
        $this->loadModel('Cuentascliente');
        $options = array(
            'contain'=>[],
            'conditions' => array(
                'Impcli.' . $this->Impcli->primaryKey => $impcliid,
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
                ],
                'Asiento'=>[
                    'Movimiento',
                    'conditions'=>[
                        'Asiento.periodo'=>$periodo
                    ]
                ]
            ),
            'conditions' => array(
                'Impcli.' . $this->Impcli->primaryKey => $impcliid,
            ),
        );
        $impcli = $this->Impcli->find('first', $options);
        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentas=false;
        foreach ($impcli['Impuesto']['Asientoestandare'] as $asientoestandar) {
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
            $impcli = $this->Impcli->find('first', $options);
        }
        //ahora vamos a listar las cuentas clientes activadas y pero con el nombre de la cuenta
        $cuentaclienteOptions = array(
            'conditions' => array(
                'Cuentascliente.cliente_id'=> $myCli['Impcli']['cliente_id']
            ),
            'fields'=>array('Cuentascliente.id','Cuenta.nombre'),
            'joins'=>array(
                array('table'=>'cuentas',
                    'alias' => 'Cuenta',
                    'type'=>'inner',
                    'conditions'=> array(
                        'Cuentascliente.cuenta_id = Cuenta.id',
                    )
                ),
            ),

        );
        $cuentasclientes=$this->Cuentascliente->find('list',$cuentaclienteOptions);
        $this->set(compact('periodo','impcli','cuentasclientes'));
        //el tema es que el asiento automatico siempre va a ser unico para un periodo para un impcli por lo que 
        //por estos datos vamos a asegurarnos de que si ya creamos el asiento, estemos editando el mismo, y no
        //creando uno nuevo cada vez que generemos este informe
    }
    public function contabilizarventa($cliid = null, $periodo = null){
        $this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Asientoestandare');
        $options = array(
            'contain'=>[],
            'conditions' => array(
                'Cliente.' . $this->Cliente->primaryKey => $cliid,
            ),
        );
        $cliente = $this->Cliente->find('first', $options);
        $options = array(
            'contain'=>array(
                'Cuenta'=>[
                    'Cuentascliente'=>[
                        'conditions'=>[
                            'Cuentascliente.cliente_id'=>$cliid
                        ]
                    ]
                ]
            ),
            'conditions' => array(
                'Asientoestandare.tipoasiento'=> 'ventas',
            ),
        );
        $asientoestandares = $this->Asientoestandare->find('all', $options);
        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentas=false;
        foreach ($asientoestandares as $asientoestandar) {
            if(count($asientoestandar['Cuenta']['Cuentascliente'])==0){
                //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                $this->Cuentascliente->create();
                $this->Cuentascliente->set('cliente_id',$cliid);
                $this->Cuentascliente->set('cuenta_id',$asientoestandar['Cuenta']['id']);
                $this->Cuentascliente->save();
                $secrearoncuentas=true;
            }
        }
        //ahora ya estamos seguros de que las cuentas clientes existen
        if($secrearoncuentas){
            $asientoestandares = $this->Asientoestandare->find('all', $options);
        }

        //ahora vamos a listar las cuentas clientes activadas y pero con el nombre de la cuenta
        $cuentaclienteOptions = array(
            'conditions' => array(
                'Cuentascliente.cliente_id'=> $cliid
            ),
            'fields'=>array('Cuentascliente.id','Cuenta.nombre'),
            'joins'=>array(
                array('table'=>'cuentas',
                    'alias' => 'Cuenta',
                    'type'=>'inner',
                    'conditions'=> array(
                        'Cuentascliente.cuenta_id = Cuenta.id',
                    )
                ),
            ),

        );
        $cuentasclientes=$this->Cuentascliente->find('list',$cuentaclienteOptions);
        //el tema es que el asiento automatico siempre va a ser unico para un periodo para un cliente por lo que
        //por estos datos vamos a asegurarnos de que si ya creamos el asiento, estemos editando el mismo, y no
        //creando uno nuevo cada vez que generemos este informe
        $options = array(
            'contain'=>array(
                'Movimiento',
            ),
            'conditions' => array(
                'Asiento.tipoasiento'=> 'ventas',
                'Asiento.periodo'=>$periodo,
                'Asiento.cliente_id'=>$cliid
            ),
        );
        $asientoyacargado = $this->Asiento->find('first', $options);
        $this->set(compact('cliid','cliente','periodo','asientoestandares','cuentasclientes','asientoyacargado'));
        //$this->autoRender=false;
        $this->layout = 'ajax';
        return;
    }
    public function contabilizarcompra($cliid = null, $periodo = null){
        $this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Asientoestandare');
        $options = array(
            'contain'=>[],
            'conditions' => array(
                'Cliente.' . $this->Cliente->primaryKey => $cliid,
            ),
        );
        $cliente = $this->Cliente->find('first', $options);
        $options = array(
            'contain'=>array(
                'Cuenta'=>[
                    'Cuentascliente'=>[
                        'conditions'=>[
                            'Cuentascliente.cliente_id'=>$cliid
                        ]
                    ]
                ]
            ),
            'conditions' => array(
                'Asientoestandare.tipoasiento'=> 'compras',
            ),
        );
        $asientoestandares = $this->Asientoestandare->find('all', $options);
        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentas=false;
        foreach ($asientoestandares as $asientoestandar) {
            if(count($asientoestandar['Cuenta']['Cuentascliente'])==0){
                //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                $this->Cuentascliente->create();
                $this->Cuentascliente->set('cliente_id',$cliid);
                $this->Cuentascliente->set('cuenta_id',$asientoestandar['Cuenta']['id']);
                $this->Cuentascliente->save();
                $secrearoncuentas=true;
            }
        }
        //ahora ya estamos seguros de que las cuentas clientes existen
        if($secrearoncuentas){
            $asientoestandares = $this->Asientoestandare->find('all', $options);
        }

        //ahora vamos a listar las cuentas clientes activadas y pero con el nombre de la cuenta
        $cuentaclienteOptions = array(
            'conditions' => array(
                'Cuentascliente.cliente_id'=> $cliid
            ),
            'fields'=>array('Cuentascliente.id','Cuenta.nombre'),
            'joins'=>array(
                array('table'=>'cuentas',
                    'alias' => 'Cuenta',
                    'type'=>'inner',
                    'conditions'=> array(
                        'Cuentascliente.cuenta_id = Cuenta.id',
                    )
                ),
            ),

        );
        $cuentasclientes=$this->Cuentascliente->find('list',$cuentaclienteOptions);
        //el tema es que el asiento automatico siempre va a ser unico para un periodo para un cliente por lo que
        //por estos datos vamos a asegurarnos de que si ya creamos el asiento, estemos editando el mismo, y no
        //creando uno nuevo cada vez que generemos este informe
        $options = array(
            'contain'=>array(
                'Movimiento',
            ),
            'conditions' => array(
                'Asiento.tipoasiento'=> 'compras',
                'Asiento.periodo'=>$periodo,
                'Asiento.cliente_id'=>$cliid
            ),
        );
        $asientoyacargado = $this->Asiento->find('first', $options);
        $this->set(compact('cliid','cliente','periodo','asientoestandares','cuentasclientes','asientoyacargado'));
        //$this->autoRender=false;
        $this->layout = 'ajax';
        return;
    }

}
