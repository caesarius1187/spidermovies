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
    public function index($ClienteId = null,$periodo = null ,$cuentacliente = null)
    {
        $this->loadModel('Movimiento');
        $this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');

        $pemes = substr($periodo, 0, 2);
        $peanio = substr($periodo, 3);

        $clienteOpc = array(
            'conditions' => array(
                'Cliente.id'=> $ClienteId
            ),
            'recursive' => -1,
        );
        $cliente = $this->Cliente->find('first', $clienteOpc);
        $this->set('cliente',$cliente);
        $this->set('periodo',$periodo);
        $AsientosOpt = [
            'contain' => [
                'Movimiento'=>[
                    'conditions'=>[
                    ]
                ]
            ],
            'conditions'=>[
                'Asiento.cliente_id'=>$ClienteId,
                'SUBSTRING(Asiento.periodo,4,7)*1 = '.$peanio.'*1',
            ]
        ];

        if($cuentacliente!=null){
            //Ahora vamos a buscar los asientos que tengas movimientos que impacten en esta cuenta cliente
            //primero vamos a buscar los movimientos que tengan esta cuenta cliente
            //y vamos a recojer los id's de los asientos esos

            $opcionesMovimientos = [
                'contain'=>[],
                'fields'=>['Movimiento.id','Movimiento.asiento_id'],
                'conditions'=>[
                    'Movimiento.cuentascliente_id'=>$cuentacliente,
                ]
            ];
            $movimientos = $this->Movimiento->find('all',$opcionesMovimientos);
            $asientosqueimpactan = [];
            foreach ($movimientos as $movimiento) {
                $asientosqueimpactan[] = $movimiento['Movimiento']['asiento_id'];
            }
            $AsientosOpt['conditions']['Asiento.id'] =  $asientosqueimpactan;
            $this->layout = 'ajax';
        }
        $asientos = $this->Asiento->find('all', $AsientosOpt);

        $this->set('asientos',$asientos);
        $cuentaclienteOptions = [
            'conditions' => [
                'Cuentascliente.cliente_id'=> $ClienteId
            ],
            'fields' => [
                'Cuentascliente.id',
                'Cuentascliente.nombre',
                'Cuenta.numero',
            ],
            'order'=>['Cuenta.numero'],
            'joins'=>[
                ['table'=>'cuentas',
                    'alias' => 'Cuenta',
                    'type'=>'inner',
                    'conditions'=> [
                        'Cuentascliente.cuenta_id = Cuenta.id',
                        'Cuenta.tipo'=>'cuenta',
                    ]
                ],
            ],
        ];
        $cuentasclientes=$this->Cuentascliente->find('list',$cuentaclienteOptions);
        $this->set('cuentasclientes',$cuentasclientes);
    }
    public function add() {
        $this->loadModel('Movimiento');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Cuenta');
        if ($this->request->is('post')) {
            $this->Asiento->create();
            $respuesta = array('respuesta'=>'');
            foreach ($this->request->data['Asiento'] as $a => $asientoAGuardar){
                $this->request->data('Asiento.'.$a.'.fecha',date('Y-m-d',strtotime($this->request->data['Asiento'][$a]['fecha'])));
                $respuesta['data']=$this->request->data;
                if ($this->Asiento->save($this->request->data['Asiento'][$a])) {
                    $respuesta['respuesta'] .= "El Asiento se guardo correctamente.</br>";
                    $asientoid=0;
                    if($this->request->data['Asiento'][$a]['id']==0){
                        $asientoid = $this->Asiento->getLastInsertID();
                    }else{
                        $asientoid = $this->request->data['Asiento'][$a]['id'];
                    }
                    foreach ($this->request->data['Asiento'][$a]['Movimiento'] as $k => $movimiento){
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

                        if ($this->Movimiento->save($movimiento)) {
                            $respuesta['respuesta'].= "El Movimiento se guardo correctamente.</br>";
                        } else {
                            $respuesta['respuesta'].="El Movimiento NO se guardo correctamente. Por favor intentelo de nuevo.</br>";
                        }
                    }
                }else {
                    $respuesta['respuesta'].="El Asiento NO se guardo correctamente. Por favor intentelo de nuevo.</br>";
                }
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
        $cuentaclienteOptions = [
            'conditions' => [
                'Cuentascliente.cliente_id' => $myCli['Impcli']['cliente_id']
            ],
            'fields'=>['Cuentascliente.id','Cuenta.nombre'],
            'joins'=>[
                ['table'=>'cuentas',
                    'alias' => 'Cuenta',
                    'type'=>'inner',
                    'conditions'=> [
                        'Cuentascliente.cuenta_id = Cuenta.id',
                    ]
                ],
            ],
        ];
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
        $this->loadModel('Venta');
        $options = array(
            'contain'=>[
                'Actividadcliente'=>[
                    'Cuentasganancia'=>['Cuentascliente'],
                ]
            ],
            'conditions' => array(
                'Cliente.' . $this->Cliente->primaryKey => $cliid,
            ),
        );

        $cliente = $this->Cliente->find('first', $options);
        $tiposDeAsientos = [];
        $tiposDeAsientos[] = 'ventas';
        $pagaCategoria = [];
        $cuentaclienteaseleccionar=[];

        foreach ($cliente['Actividadcliente'] as $actividadcliente){
            if(count($actividadcliente['Cuentasganancia'])>0){
                $categoriaActividad = $actividadcliente['Cuentasganancia'][0]['categoria'];
                if(!in_array($categoriaActividad,array('terceracateg45','cuartacateg'), true )){
                    if(!in_array($categoriaActividad,$tiposDeAsientos)){
                        $tiposDeAsientos[]=$categoriaActividad;
                        $pagaCategoria[] = $categoriaActividad;
                    }
                }else{
                    $cuentaclienteaseleccionar[]=$actividadcliente['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
                }
            }
        }
        if(count($pagaCategoria)==0&&count($cuentaclienteaseleccionar)==0){
            if($this->request->is('ajax')){
                $this->layout = 'ajax';
            }
            $this->set('error','Atencion: 
            No se configuro el impuesto Ganancias en el organismo AFIP, configurelo y vuelva a intentar 
            contabilizar');
            return;
        }
        $options = array(
            'contain'=>array(
                'Cuenta'=>[
                    'Cuentascliente'=>[
                        'Cuenta',
                        'conditions'=>[
                            'Cuentascliente.cliente_id'=>$cliid
                        ]
                    ]
                ]
            ),
            'conditions' => array(
                'Asientoestandare.tipoasiento'=> $tiposDeAsientos,
            ),
        );
        if(count($cuentaclienteaseleccionar)!=0){
            unset($options['conditions']);
            $options['conditions']=[
                'OR'=>[
                    'Asientoestandare.tipoasiento'=> $tiposDeAsientos,
                    'Asientoestandare.cuenta_id'=> $cuentaclienteaseleccionar,
                ]
            ];
        }
        $asientoestandares = $this->Asientoestandare->find('all', $options);
        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentas=false;
        foreach ($asientoestandares as $asientoestandar) {
            if(count($asientoestandar['Cuenta']['Cuentascliente'])==0){
                //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                //Pero solo si es de la 1ra , 2da o 3ra categoria
                if(
                !in_array($asientoestandar['Asientoestandare']['tipoasiento'],
                    ['terceracateg45','cuartacateg'], true )
                ){
                    $this->Cuentascliente->create();
                    $this->Cuentascliente->set('cliente_id',$cliid);
                    $this->Cuentascliente->set('cuenta_id',$asientoestandar['Cuenta']['id']);
                    $this->Cuentascliente->set('nombre',$asientoestandar['Cuenta']['nombre']);
                    $this->Cuentascliente->save();
                    $secrearoncuentas=true;
                }
            }
        }
        if(count($cuentaclienteaseleccionar)){

        }
        //ahora ya estamos seguros de que las cuentas clientes existen
        if($secrearoncuentas){
            $asientoestandares = $this->Asientoestandare->find('all', $options);
        }
        /*Aca vamos a armar la lista de cuentas incluidas en el asiento estandar para traer las cuentas cliente relacionadas
        */
        //ahora vamos a listar las cuentas clientes activadas y pero con el nombre de la cuenta
        $cuentaclienteOptions = [
            'conditions' => [
                'Cuentascliente.cliente_id'=> $cliid
            ],
            'fields' => [
                'Cuentascliente.id',
                'Cuentascliente.nombre',
                'Cuenta.numero',
            ],
            'order'=>['Cuenta.numero'],
            'joins'=>[
                ['table'=>'cuentas',
                    'alias' => 'Cuenta',
                    'type'=>'inner',
                    'conditions'=> [
                        'Cuentascliente.cuenta_id = Cuenta.id',
                    ]
                ],
            ],
        ];
        $cuentasclientes=$this->Cuentascliente->find('list',$cuentaclienteOptions);
        //el tema es que el asiento automatico siempre va a ser unico para un periodo para un cliente por lo que
        //por estos datos vamos a asegurarnos de que si ya creamos el asiento, estemos editando el mismo, y no
        //creando uno nuevo cada vez que generemos este informe
        $options = [
            'contain'=>[
                'Movimiento',
            ],
            'conditions' => [
                'Asiento.tipoasiento'=> 'ventas',
                'Asiento.periodo'=>$periodo,
                'Asiento.cliente_id'=>$cliid
            ],
        ];
        $asientoyacargado = $this->Asiento->find('first', $options);
        //Vamos a agrupar y sumar las ventas gravadas y las no gravadas
        $conditionsVentaGravada = [
            'contain'=>[
                'Actividadcliente'=>[
                    'Cuentasganancia'=>['Cuentascliente']
                ],
            ],
            'fields'=>[
                'Venta.tipodebito','Venta.actividadcliente_id','sum(total) as total,sum(neto) as neto,sum(iva) as iva,
                sum(nogravados) as nogravados, sum(excentos) as exentos, sum(ivapercep) as ivapercep, 
                sum(iibbpercep) as iibbpercep,sum(actvspercep) as actvspercep,sum(impinternos) as impinternos'
            ],
            'group'=>
                ['Venta.tipodebito','Venta.actividadcliente_id'],
            'conditions'=>[
                'Venta.cliente_id'=>$cliid,
                'Venta.periodo'=>$periodo,
            ],
        ];
        $ventasgravadas = $this->Venta->find('all',$conditionsVentaGravada);
        $this->set(compact('cliid','cliente','periodo','pagaCategoria','asientoestandares','cuentasclientes'
            ,'asientoyacargado','ventasgravadas'));
        if($this->request->is('ajax')){
            $this->layout = 'ajax';
        }
        return;
    }
    public function contabilizarcompra($cliid = null, $periodo = null){
        $this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Asientoestandare');
        $this->loadModel('Compra');
        $options = array(
            'contain'=>[
                'Actividadcliente'=>[
                    'Cuentasganancia'=>['Cuentascliente'],
                ]
            ],
            'conditions' => array(
                'Cliente.' . $this->Cliente->primaryKey => $cliid,
            ),
        );

        $cliente = $this->Cliente->find('first', $options);
        $tiposDeAsientos = [];
        $tiposDeAsientos[] = 'compras';
        $pagaCategoria = [];
        $cuentaclienteaseleccionar=[];

        foreach ($cliente['Actividadcliente'] as $actividadcliente){
            if(count($actividadcliente['Cuentasganancia'])>0){
                $categoriaActividad = $actividadcliente['Cuentasganancia'][0]['categoria'];
                if(!in_array($categoriaActividad,$tiposDeAsientos)){
                    $tiposDeAsientos[] = "compra".$categoriaActividad;
                    $pagaCategoria[]   = "compra".$categoriaActividad;
                }
            }
        }
        if(count($pagaCategoria)==0){
            if($this->request->is('ajax')){
                $this->layout = 'ajax';
            }
            $this->set('error','Atencion: 
            No se configuro el impuesto Ganancias en el organismo AFIP, configurelo y vuelva a intentar 
            contabilizar');
            return;
        }
        $options = array(
            'contain'=>array(
                'Cuenta'=>[
                    'Cuentascliente'=>[
                        'Cuenta',
                        'conditions'=>[
                            'Cuentascliente.cliente_id'=>$cliid
                        ]
                    ]
                ]
            ),
            'conditions' => array(
                'Asientoestandare.tipoasiento'=> $tiposDeAsientos,
            ),
        );
        if(count($cuentaclienteaseleccionar)!=0){
            unset($options['conditions']);
            $options['conditions']=[
                'OR'=>[
                    'Asientoestandare.tipoasiento'=> $tiposDeAsientos,
                    'Asientoestandare.cuenta_id'=> $cuentaclienteaseleccionar,
                ]
            ];
        }
        $asientoestandares = $this->Asientoestandare->find('all', $options);
        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentas=false;
        foreach ($asientoestandares as $asientoestandar) {
            if(count($asientoestandar['Cuenta']['Cuentascliente'])==0){
                //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                $this->Cuentascliente->create();
                $this->Cuentascliente->set('cliente_id',$cliid);
                $this->Cuentascliente->set('cuenta_id',$asientoestandar['Cuenta']['id']);
                $this->Cuentascliente->set('nombre',$asientoestandar['Cuenta']['nombre']);
                $this->Cuentascliente->save();
                $secrearoncuentas=true;
            }
        }
        //ahora ya estamos seguros de que las cuentas clientes existen
        if($secrearoncuentas){
            $asientoestandares = $this->Asientoestandare->find('all', $options);
        }
        /*Aca vamos a armar la lista de cuentas incluidas en el asiento estandar para traer las cuentas cliente relacionadas
        */
        //ahora vamos a listar las cuentas clientes activadas y pero con el nombre de la cuenta
        $cuentaclienteOptions = array(
            'conditions' => array(
                'Cuentascliente.cliente_id'=> $cliid
            ),
            'fields' => [
                'Cuentascliente.id',
                'Cuentascliente.nombre',
                'Cuenta.numero',
            ],
            'order'=>['Cuenta.numero'],
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

        //Vamos a agrupar y sumar las compras gravadas y las no gravadas
        $conditionsCompraGravada = [
            'contain'=>[
                'Actividadcliente'=>[
                    'Cuentasganancia'=>['Cuentascliente']
                ],
            ],
            'fields'=>[
                'Compra.tipogasto_id','Compra.tipocredito','Compra.imputacion','Compra.actividadcliente_id','sum(total) as total,
                sum(neto) as neto,sum(iva) as iva,sum(nogravados) as nogravados, sum(exentos) as exentos, 
                sum(ivapercep) as ivapercep,sum(iibbpercep) as iibbpercep,sum(actvspercep) as actvspercep,
                sum(impinternos) as impinternos,sum(impcombustible) as impcombustible'
            ],
            'group'=>
                ['Compra.tipocredito','Compra.actividadcliente_id','Compra.tipogasto_id'],
            'conditions'=>[
                'Compra.cliente_id'=>$cliid,
                'Compra.periodo'=>$periodo,
            ],
        ];
        $comprasgravadas = $this->Compra->find('all',$conditionsCompraGravada);
        $this->set(compact('cliid','cliente','periodo','pagaCategoria','asientoestandares','cuentasclientes','asientoyacargado',
            'comprasgravadas'));

        if($this->request->is('ajax')){
            $this->layout = 'ajax';
        }

        return;
    }
    public function contabilizarbanco($cliid = null, $periodo = null,$bancimpcli = null, $cbuid = null){
        $this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Asientoestandare');
        $this->loadModel('Movimientosbancarios');
        $this->loadModel('Cuenta');
        $options = [
            'contain'=>[
                'Impcli'=>[
                    'Cbu'=>[
                        'Cuentascliente'=>[
                          'Cuenta'
                        ],
                        'conditions'=>[
                            'Cbu.id' => $cbuid,
                        ]
                    ],
                    'Impuesto',
                    'conditions'=>[
                        'Impcli.id' => $bancimpcli,
                    ]
                ]
            ],
            'conditions' => [
                'Cliente.' . $this->Cliente->primaryKey => $cliid,
            ],
        ];
        $cliente = $this->Cliente->find('first', $options);
        $cuentasDeMovimientoBancario = $this->Cuenta->cuentasDeMovimientoBancario;
        $options = array(
            'contain'=>[
                'Cuenta',
                'Movimientosbancario'=>[
                    'conditions'=>[
                        'Movimientosbancario.periodo'=>$periodo,
                        'Movimientosbancario.cbu_id'=>$cbuid
                    ],
                ],
            ],
            'conditions'=>[
                'Cuentascliente.cliente_id'=>$cliid,
                'Cuentascliente.cuenta_id'=>$cuentasDeMovimientoBancario,
            ],
        );
        $miscuentasclientes = $this->Cuentascliente->find('all', $options);
        /*Aca vamos a armar la lista de cuentas incluidas en el asiento estandar
        para traer las cuentas cliente relacionadas*/
        //ahora vamos a listar las cuentas clientes activadas y pero con el nombre de la cuenta
        $cuentaclienteOptions = [
            'conditions' => [
                'Cuentascliente.cliente_id'=> $cliid
            ],
            'fields' => [
                'Cuentascliente.id',
                'Cuentascliente.nombre',
                'Cuenta.numero',
            ],
            'order'=>['Cuenta.numero'],
            'joins'=>[
                [
                    'table'=>'cuentas',
                    'alias' => 'Cuenta',
                    'type'=>'inner',
                    'conditions'=> [
                        'Cuentascliente.cuenta_id = Cuenta.id',
                    ]
                ],
            ],
        ];
        $cuentasclientes=$this->Cuentascliente->find('list',$cuentaclienteOptions);
        //el tema es que el asiento automatico siempre va a ser unico para un periodo para un cliente por lo que
        //por estos datos vamos a asegurarnos de que si ya creamos el asiento, estemos editando el mismo, y no
        //creando uno nuevo cada vez que generemos este informe
        $options = [
            'contain'=>[
                'Movimiento',
            ],
            'conditions' => [
                'Asiento.tipoasiento'=> ['bancos','bancosretiros'],
                'Asiento.periodo'=>$periodo,
                'Asiento.cliente_id'=>$cliid,
                'Asiento.cbu_id'=>$cbuid,
            ],
        ];
        $asientoyacargado = $this->Asiento->find('all', $options);
        //Vamos a agrupar y sumar las compras gravadas y las no gravadas
        $this->set(compact('cliid','cliente','cbuid','bancimpcli','periodo','cuentasclientes','asientoyacargado','miscuentasclientes'));
        if($this->request->is('ajax')){
            $this->layout = 'ajax';
        }
        return;
    }

}
