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
            'contain' => [],
        );
        $cliente = $this->Cliente->find('first', $clienteOpc);
        $this->set('cliente',$cliente);
        $this->set('periodo',$periodo);

        $fechadeconsulta = date('Y/m/d',strtotime("01-".$pemes."-".$peanio));

        if(!isset($cliente['Cliente']['fchcorteejerciciofiscal'])||is_null($cliente['Cliente']['fchcorteejerciciofiscal'])||$cliente['Cliente']['fchcorteejerciciofiscal']==""){
            $this->Session->setFlash(__('No se ha configurado fecha decorte de ejercicio fiscal para este
			 ccontribuyente .'));
            $fechadecorteAñoActual = date('Y/m/d',strtotime("01-01-".$peanio));

        }else{
            $fechadecorteAñoActual = date('Y/m/d',strtotime($cliente['Cliente']['fchcorteejerciciofiscal']."-".$peanio));
        }
        $fechaInicioConsulta = "";
        $fechaFinConsulta = "";
        if($fechadeconsulta<$fechadecorteAñoActual){
            $fechaInicioConsulta =  date('Y/m/d',strtotime($fechadecorteAñoActual." - 1 Years + 1 days"));
        //			$fechaFinConsulta =  $fechadecorteAñoActual;
        }else {
            $fechaInicioConsulta = date('Y/m/d', strtotime($fechadecorteAñoActual . " + 1 days"));;
        //			$fechaFinConsulta = date('Y/m/d', strtotime($fechadecorteAñoActual . " + 1 Years"));
        }
        //la fecha fin consulta es esta por quesolo vamos a ver hasta el ultimo dia del periodo que estamos
        // consultando
        $fechaFinConsulta =  date('Y/m/t',strtotime($fechadeconsulta));
        $this->set('fechaInicioConsulta',$fechaInicioConsulta);
        $this->set('fechaFinConsulta',$fechaFinConsulta);

        $AsientosOpt = [
            'contain' => [
                'Movimiento'=>[
                    'conditions'=>[
                    ]
                ]
            ],
            'conditions'=>[
                'Asiento.cliente_id'=>$ClienteId,
                "Asiento.fecha >= '".date('Y-m-d', strtotime($fechaInicioConsulta))."'",
                "Asiento.fecha <= '".date('Y-m-d', strtotime($fechaFinConsulta))."'",
            ],
            'order'=>[
                "Asiento.fecha"
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

            $optionsCuentacliente = [
                'contain'=>[
                    'Cuenta'
                ],
                'conditions'=>[
                    'Cuentascliente.id'=>$cuentacliente,
                ]
            ];
            $cuentasclienteseleccionada = $this->Cuentascliente->find('first',$optionsCuentacliente);
            $this->set('cuentasclienteseleccionada',$cuentasclienteseleccionada);
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
        if($this->request->is('ajax')){
            $this->set('isajaxrequest',1);
        }else{
            $this->set('isajaxrequest',0);
        }
    }
    
    public function eliminar($asiid = null) {
        $borreMovimientos = $this->Asiento->Movimiento->deleteAll(['Movimiento.asiento_id' => $asiid], false);
        $borreAsiento = $this->Asiento->deleteAll(['Asiento.id' => $asiid], false);
        $respuesta = array('respuesta'=>'');
        $respuesta['error']=0;
        if($borreMovimientos&&$borreAsiento){
            $respuesta['respuesta']="Asiento Eliminado con Exito";
        }else{
            $respuesta['respuesta']="Error al tratar de eliminae el asiento. "
                    . "Por favor intente de nuevo mas tarde";
            $respuesta['error']=1;
        }
        $respuesta['data']=$this->request->data;
        $this->set('data',$respuesta);
        $this->autoRender=false;
        $this->layout = 'ajax';
        $this->render('serializejson');
        return;
    }
    public function add() {
        $this->loadModel('Movimiento');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Cuenta');
        $this->loadModel('Cliente');
        $respuesta = array('respuesta'=>'','proceso'=>'');
        $respuesta['error']=0;
        if ($this->request->is('post')) {
            //haber la onda es que si esta echo el asiento de ganancias, entonces que no se pueda hacer NINGUN otro asiento
            //$asiento0ano = substr($this->request->data['Asiento'][0]['periodo'], 3);
            reset($this->request->data['Asiento']);
            $first_key = key($this->request->data['Asiento']);
            $asiento0ano = date('Y',strtotime($this->request->data['Asiento'][$first_key]['fecha']));
             $options = array(
                'contain'=>[
                    'Impcli'=>[
                        'Asiento'=>[
                            'conditions'=>[
                                'Asiento.tipoasiento'=>'impuestos',
                                'SUBSTRING(Asiento.periodo,4,7)'=>$asiento0ano,
                            ]
                        ],
                        'conditions'=>[
                            'Impcli.impuesto_id'=>[160,5]
                        ]
                    ]
                ],
                'conditions' => array(
                    'Cliente.' . $this->Cliente->primaryKey => $this->request->data['Asiento'][$first_key]['cliente_id'],
                ),
            );
            $cliente = $this->Cliente->find('first', $options);
            $fechaAsientoGanancias="";
            $fechaAsientoaguardar="";
            if(isset($cliente['Impcli'][0])){
                if(count($cliente['Impcli'][0]['Asiento'])>0){
                    $fechaAsientoGanancias = strtotime($cliente['Impcli'][0]['Asiento'][0]['fecha']); 
                    $fechaAsientoaguardar =  strtotime($this->request->data['Asiento'][$first_key]['fecha']); 
                    $respuesta['fechaAsientoGanancias']=date('d-m-Y',$fechaAsientoGanancias);
                    $respuesta['fechaAsientoaguardar']=date('d-m-Y',$fechaAsientoaguardar);
                    if($fechaAsientoGanancias>$fechaAsientoaguardar){
                         //entonces tengo el asiento de este cliente en ganancias NO PUEDO GUARDAR NADA
                        $respuesta['error']=2;
                        $respuesta['respuesta'].="Se ha creado el asiento de devengamiento para el impuesto Ganancias, en este periodo, para este cliente.</br>"
                                ."No se puede guardar mas asientos hasta que elimine el asiento en cuestion.";
                        $respuesta['data']=$this->request->data;
                        $this->set('data',$respuesta);
                        $this->autoRender=false;
                        $this->layout = 'ajax';
                        $this->render('serializejson');
                        return;
                    }
                }
            }
            $this->Asiento->create();
            foreach ($this->request->data['Asiento'] as $a => $asientoAGuardar){
                //lo primero que vamos a revisar es que el asiento tenga debe y haber iguales
                //sino es asi no se guarda nada
                $totaldebe = 0;
                $totalhaber = 0;
                if(!isset($this->request->data['Asiento'][$a]['Movimiento'])){
                    //$respuesta['error']=2;
                    //$respuesta['respuesta'].= $a.": Asiento sin movimientos, no se guardará. ";
                    continue;
                }
                foreach ($this->request->data['Asiento'][$a]['Movimiento'] as $k => $movimiento){
                        $totaldebe += $movimiento['debe']*1;
                        $totalhaber += $movimiento['haber']*1;
                }
                if((round($totaldebe, 2)-round($totalhaber, 2))!=0){
                    //Este asiento NO se debe GUARDAR
                    $respuesta['error']=1;
                    $respuesta['respuesta'].="El Asiento NO se guardo correctamente. El total de debe y haber no coincide. "
                            . "Por favor corrijalo e intentelo de nuevo.</br>"
                            . "Total Debe: ".round($totaldebe, 2)." distinto de Total Haber: ".round($totalhaber, 2)."</br>"
                            .'La diferencia es '.(round($totaldebe, 2)-round($totalhaber, 2));
                    $respuesta['data']=$this->request->data;
                    $this->set('data',$respuesta);
                    $this->autoRender=false;
                    $this->layout = 'ajax';
                    $this->render('serializejson');
                    return;
                }
                
                //si el numero es 0 vamos a buscar el numero mas alto para editarlo                
                if($this->request->data['Asiento'][$a]['id']==0){
                    $maxnumeroasiento = $this->Asiento->find('all',[
                            'conditions' => [
                                'Asiento.cliente_id' => $this->request->data['Asiento'][$a]['cliente_id'],
                            ],
                            'fields' => array('MAX(Asiento.numero) AS maxnumero'),
                            'group by' => 'Asiento.cliente_id',
                        ]
                    );
                    $maxnumero= $maxnumeroasiento[0][0]['maxnumero']*1+1;
                    $this->request->data['Asiento'][$a]['numero']=$maxnumero;
                }else{
                    $asiento = $this->Asiento->find('first',[
                            'contain' => [],
                            'conditions' => [
                                'Asiento.id' => $this->request->data['Asiento'][$a]['id'],
                            ],
                        ]
                    );
                    $this->request->data['Asiento'][$a]['numero']=$asiento['Asiento']['numero'];
                }
                $this->request->data('Asiento.'.$a.'.fecha',date('Y-m-d',strtotime($this->request->data['Asiento'][$a]['fecha'])));

                if ($this->Asiento->save($this->request->data['Asiento'][$a])) {
                    $respuesta['respuesta'] .= "El Asiento se guardo correctamente.";
                    $asientoid=0;
                    if($this->request->data['Asiento'][$a]['id']==0){
                        $asientoid = $this->Asiento->getLastInsertID();
                    }else{
                        $asientoid = $this->request->data['Asiento'][$a]['id'];
                    }
                    if(!isset($this->request->data['Asiento'][$a]['Movimiento'])){
                        continue;
                    }
                 
                    foreach ($this->request->data['Asiento'][$a]['Movimiento'] as $k => $movimiento){
                        //si el movimiento tiene haber 0 y debe 0 no se deberia guardar a menos que sea un asiento
                        //que ya existia previamente por lo que se esta modificando a 0 sus valores
                        $debe = $movimiento['debe']*1;
                        $haber = $movimiento['haber']*1;
                        $tienevalor=false;
                        $movimientoyaguardado=false;
                        $respuesta['id']="notiene";
                        $respuesta['tienevalor']="no tiene";
                        $respuesta['movimientoyaguardado']="no tiene";
                        if($debe!=0||$haber!=0){
                            $tienevalor=true;
                            $respuesta['debe']=$debe;
                            $respuesta['haber']=$haber;
                            $respuesta['tienevalor']="si tiene";
                        }
                        if($movimiento['id']*1 != 0){
                            //entonces el asiento ya estaba guardado y se puede estar modificando
                            $movimientoyaguardado=true;
                            $respuesta['id']=$movimiento['id'];
                            $respuesta['movimientoyaguardado']="ya guardado";
                        }
                        if(!$tienevalor&&!$movimientoyaguardado){
                            //no tiene valor y no estaba guardado entonces no hago nada
                            continue;
                        }
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
                                    
                                    $respuesta['error']=8;
                                    $respuesta['respuesta'].='Error al guardar cuenta. Por favor intente nuevamente.</br>';
                                }
                            }
                        }

                        if ($this->Movimiento->save($movimiento)) {
                            if($this->request->data['Asiento'][$a]['Movimiento'][$k]['id']==0){
                                $movimientoid = $this->Movimiento->getLastInsertID();
                            }else{
                                $movimientoid = $this->request->data['Asiento'][$a]['Movimiento'][$k]['id'];
                            }
                            $this->request->data['Asiento'][$a]['Movimiento'][$k]['id']=$movimientoid;
                            $respuesta['proceso'].= "El Movimiento se guardo correctamente.</br>";
                        } else {
                            $respuesta['proceso'].="El Movimiento NO se guardo correctamente. Por favor intentelo de nuevo.</br>";
                        }
                    }
                }else {
                    $respuesta['respuesta'].="El Asiento NO se guardo correctamente. Por favor intentelo de nuevo.</br>";
                }
            }

        }
        $respuesta['data']=$this->request->data;
        $this->set('data',$respuesta);
        $this->autoRender=false;
        $this->layout = 'ajax';
        $this->render('serializejson');
        return;
    }
    public function contabilizarimpuesto($impcliid = null, $periodo = null){
        //esta funcion la vamos a usar solo para ganancias por ahora
        
        $this->loadModel('Impcli');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Cuenta');
        $pemes = substr($periodo, 0, 2);
        $peanio = substr($periodo, 3);
        
        $esMayorQueBaja = array(
            //HASTA es mayor que el periodo
            'OR'=>array(
                'Actividadcliente.baja'=>null,
                'SUBSTRING(Actividadcliente.baja,4,7)*1 < '.$peanio.'*1',
                'AND'=>array(
                    'SUBSTRING(Actividadcliente.baja,4,7)*1 <= '.$peanio.'*1',
                    'SUBSTRING(Actividadcliente.baja,1,2) <= '.$pemes.'*1'
                ),
            )
        );
         
        $options = array(
            'contain'=>[
                'Cliente'=>[
                    'Actividadcliente'=>[
                        'Cuentasganancia'=>[],
                        'conditions'=>$esMayorQueBaja
                    ]
                ]
            ],
            'conditions' => array(
                'Impcli.' . $this->Impcli->primaryKey => $impcliid,
            ),
        );
        $myCli = $this->Impcli->find('first', $options);
        
        /*INICIO CUENTASPAGO*/
                     
        $tiene3ra = false;
        foreach ($myCli['Cliente']['Actividadcliente'] as $actividadcliente){
            foreach ($actividadcliente['Cuentasganancia'] as $cuentasganancia){
                if( $cuentasganancia['categoria'] == 'terceracateg'){
                    $tiene3ra = true;
                }
            }
        }
        
        /*FIN CUENTAS PAGO*/
        
        
        $options = array(
            'contain'=>array(
                'Impuesto'=>[
                    'Asientoestandare'=>[
                        'conditions'=>[
                                    'Asientoestandare.tipoasiento'=>'impuestos'
                                ],
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
                        'Asiento.periodo'=>$periodo,
                        'Asiento.tipoasiento'=>'impuestos',
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
                $this->Cuentascliente->set('nombre',$asientoestandar['Cuenta']['nombre']);
                $this->Cuentascliente->set('cuenta_id',$asientoestandar['Cuenta']['id']);
                $this->Cuentascliente->save();
                $secrearoncuentas=true;
            }
        }
        //ahora ya estamos seguros de que las cuentas clientes existen
        if($secrearoncuentas){
            $impcli = $this->Impcli->find('first', $options);
        }
        $options2 = array(
            'contain'=>array(
                'Impuesto'=>[
                    'Asientoestandare'=>[
                        'conditions'=>[
                                    'Asientoestandare.tipoasiento'=>'impuestos2'
                                ],
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
                        'Asiento.periodo'=>$periodo,
                        'Asiento.tipoasiento'=>'impuestos2'
                    ]
                ]
            ),
            'conditions' => array(
                'Impcli.' . $this->Impcli->primaryKey => $impcliid,
            ),
        );
        $impcli2 = $this->Impcli->find('first', $options2);
        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentas=false;
        foreach ($impcli2['Impuesto']['Asientoestandare'] as $asientoestandar) {
            if(count($asientoestandar['Cuenta']['Cuentascliente'])==0){
                //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                $this->Cuentascliente->create();
                $this->Cuentascliente->set('cliente_id',$myCli['Impcli']['cliente_id']);
                $this->Cuentascliente->set('nombre',$asientoestandar['Cuenta']['nombre']);                
                $this->Cuentascliente->set('cuenta_id',$asientoestandar['Cuenta']['id']);
                $this->Cuentascliente->save();
                $secrearoncuentas=true;
            }
        }
        //ahora ya estamos seguros de que las cuentas clientes existen
        if($secrearoncuentas){
            $impcli2 = $this->Impcli->find('first', $options2);
        }
        
        $options = array(
            'contain'=>array(
                'Impuesto'=>[
                    'Asientoestandare'=>[
                        'conditions'=>[
                                    'Asientoestandare.tipoasiento'=>'apagar'
                                ],
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
                        'Asiento.periodo'=>$periodo,
                        'Asiento.tipoasiento'=>'pagos'
                    ]
                ]
            ),
            'conditions' => array(
                'Impcli.' . $this->Impcli->primaryKey => $impcliid,
            ),
        );
        $impclipago = $this->Impcli->find('first', $options);
        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentas=false;
        foreach ($impclipago['Impuesto']['Asientoestandare'] as $asientoestandar) {
            if(count($asientoestandar['Cuenta']['Cuentascliente'])==0){
                //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                $this->Cuentascliente->create();
                $this->Cuentascliente->set('cliente_id',$myCli['Impcli']['cliente_id']);
                $this->Cuentascliente->set('nombre',$asientoestandar['Cuenta']['nombre']);                
                $this->Cuentascliente->set('cuenta_id',$asientoestandar['Cuenta']['id']);
                $this->Cuentascliente->save();
                $secrearoncuentas=true;
            }
        }
        //ahora ya estamos seguros de que las cuentas clientes existen
        if($secrearoncuentas){
            $impclipago = $this->Impcli->find('first', $options);
        }
        
        if($tiene3ra){
            $cuentasDeAsientoPago=[
                    '5',/*110101002 Caja Efectivo*/
            ];
            switch ($impcli["Impuesto"]["organismo"]){
                case 'afip':
                    $cuentasDeAsientoPago[]='1575'/*210701001 Plan de Pagos AFIP N°*/;
                    $cuentasDeAsientoPago[]='2523'/*505040101 Intereses Generales*/;
                    break;
                case 'dgr':
                    $cuentasDeAsientoPago[]='1597'/*210702001 Plan de Pagos DGR N°*/;
                    $cuentasDeAsientoPago[]='2526'/*505040201 Intereses Generales*/;
                    break;
                case 'dgrm':
                    $cuentasDeAsientoPago[]='1604'/*210703001 Planes de Pago DGRM°*/;
                    $cuentasDeAsientoPago[]='2529'/*505040301 Intereses Generales*/;
                    break;
                case 'otros':
                    break;
                case 'sindicato':
                        $cuentasDeAsientoPago[]='2529'/*505040301 Intereses Generales*/;
                    break;
                case 'banco':
                    break;
            }
        }else{
            $cuentasDeAsientoPago=[
                    '1069',/*130113001 Dinero en Efectivo*/
            ];
            switch ($impcli["Impuesto"]["organismo"]){
                case 'afip':
                    $cuentasDeAsientoPago[]='3499'/*230102101 Plan de Pagos AFIP N°*/;
                    $cuentasDeAsientoPago[]='2523'/*505040101 Intereses Generales*/;
                    break;
                case 'dgr':
                    $cuentasDeAsientoPago[]='3508'/*230102150 Plan de Pagos DGR N°*/;
                    $cuentasDeAsientoPago[]='2526'/*505040201 Intereses Generales*/;
                    break;
                case 'dgrm':
                    $cuentasDeAsientoPago[]='3518'/*230102201 PPlan de Pagos DGRM N°*/;
                    $cuentasDeAsientoPago[]='2529'/*505040301 Intereses Generales*/;
                    break;
                case 'otros':
                    break;
                case 'sindicato':
                        $cuentasDeAsientoPago[]='2526'/*505040201 Intereses Generales*/;
                    break;
                case 'banco':
                    break;
            }
        }
        //Aca vamos a agregar el plan de pago y la cuenta de intereses segun que Tipo de organismo sea
        $optionsCuentasclientes = array(
            'contain'=>[
                'Cuentascliente'=>[
                    'conditions'=>[
                        'Cuentascliente.cliente_id'=>$myCli['Impcli']['cliente_id']
                    ]
                ]
            ],
            'conditions' => array('Cuenta.id' => $cuentasDeAsientoPago)
        );
        $cuentaspagoimpuestos = $this->Cuenta->find('all', $optionsCuentasclientes);
        $secrearoncuentas = false;
        foreach ($cuentaspagoimpuestos as $cuentaspagoimpuesto) {
            if(count($cuentaspagoimpuesto['Cuentascliente'])==0){
                $this->Cuentascliente->create();
                $this->Cuentascliente->set('cliente_id',$myCli['Impcli']['cliente_id']);
                $this->Cuentascliente->set('cuenta_id',$cuentaspagoimpuesto['Cuenta']['id']);
                $this->Cuentascliente->set('nombre',$cuentaspagoimpuesto['Cuenta']['nombre']);
                $this->Cuentascliente->save();
                $secrearoncuentas=true;
            }
        }
        if($secrearoncuentas){
                $cuentaspagoimpuestos = $this->Cuenta->find('all', $optionsCuentasclientes);
        }
        $this->set('cuentaspagoimpuestos', $cuentaspagoimpuestos);
        
        
        //ahora vamos a listar las cuentas clientes activadas y pero con el nombre de la cuenta
        $cuentaclienteOptions = [
            'conditions' => [
                'Cuentascliente.cliente_id' => $myCli['Impcli']['cliente_id']
            ],
            'fields'=>[
                'Cuentascliente.id',
                'Cuentascliente.nombre',
                'Cuenta.numero',
            ],
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
        $this->set(compact('periodo','impcli','impcli2','impclipago','cuentasclientes'));
        //el tema es que el asiento automatico siempre va a ser unico para un periodo para un impcli por lo que
        //por estos datos vamos a asegurarnos de que si ya creamos el asiento, estemos editando el mismo, y no
        //creando uno nuevo cada vez que generemos este informe
        if($this->request->is('ajax')){
            $this->layout = 'ajax';
        }
    }
    public function contabilizarventa($cliid = null, $periodo = null){
        $this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Asientoestandare');
        $this->loadModel('Venta');
        $this->loadModel('Tipogasto');
        $pemes = substr($periodo, 0, 2);
        $peanio = substr($periodo, 3);
        $esMayorQueBaja = array(
            //HASTA es mayor que el periodo
            'OR'=>array(
                'Actividadcliente.baja'=>null,
                'SUBSTRING(Actividadcliente.baja,4,7)*1 < '.$peanio.'*1',
                'AND'=>array(
                    'SUBSTRING(Actividadcliente.baja,4,7)*1 <= '.$peanio.'*1',
                    'SUBSTRING(Actividadcliente.baja,1,2) <= '.$pemes.'*1'
                ),
            )
        );
        $options = array(
            'contain'=>[
                'Actividadcliente'=>[
                    'Cuentasganancia'=>['Cuentascliente'],
                    'conditions'=>$esMayorQueBaja
                ]
            ],
            'conditions' => array(
                'Cliente.' . $this->Cliente->primaryKey => $cliid,
            ),
        );

        $cliente = $this->Cliente->find('first', $options);
        $tiposDeAsientos = [];
        $tiposDeAsientos[] = 'ventas';
        $tiposDeAsientosCosto = [];
        $pagaCategoria = [];
        $cuentaclienteaseleccionar=[];

        $tieneGananciasConfigurado = 1;
        foreach ($cliente['Actividadcliente'] as $actividadcliente){
            if(count($actividadcliente['Cuentasganancia'])>0){
                $categoriaActividad = $actividadcliente['Cuentasganancia'][0]['categoria'];
                $tiposDeAsientos[]=$categoriaActividad;
                $pagaCategoria[] = $categoriaActividad;
        //                if(!in_array($categoriaActividad,array('terceracateg45','cuartacateg'), true )){
        //                    if(!in_array($categoriaActividad,$tiposDeAsientos)){
        //                        $tiposDeAsientos[]=$categoriaActividad;
        //                        $pagaCategoria[] = $categoriaActividad;
        //                    }
        //                }else{
        ////                    $cuentaclienteaseleccionar[]=$actividadcliente['Cuentasganancia'][0]['Cuentascliente']['cuenta_id'];
        //                }
            }else{
                $tieneGananciasConfigurado *= 0;
            }
        }
        if($tieneGananciasConfigurado==0){
            if($this->request->is('ajax')){
                $this->layout = 'ajax';
            }
            $this->set('error','Atencion: 
            No se configuro el impuesto Ganancias en el organismo AFIP, configurelo y vuelva a intentar 
            contabilizar');
            return;
        }
        $optionVentas=[
            'contain'=>[
                'Actividadcliente'=>[
                    'Cuentasganancia'
                ],
                'Bienesdeuso'=>[
                    'Cuentaclientevalororigen',
                    'Cuentaclienteactualizacion',
                    'Cuentaclienteterreno',
                    'Cuentaclienteedificacion',
                    'Cuentaclientemejora',
                ],
                'Comprobante'=>[
                ],
                'Tipogasto'=>[
                ],
            ],
            'conditions'=>[
                'Venta.tipogasto_id'=>$this->Tipogasto->ingresosBienDeUso,
                'Venta.periodo'=>$periodo,
                'Venta.cliente_id'=>$cliid
            ]
        ];
        $ventasbiendeuso = $this->Venta->find('all',$optionVentas);
        foreach ($ventasbiendeuso as $venta){
            //aca vamos a recorrer las ventas entonces vamos a ver si hay algun bien de uso de uso personal
            //para agregar el tipo de asiento "bien de uso personal"
            
            if(isset($venta['Bienesdeuso'][0])){
                    if($venta['Bienesdeuso'][0]['bienusopersonal']){
                        $tiposDeAsientosCosto[]= "costoprimeracateg";
                    }else{
                        $tiposDeAsientosCosto[]= "costoprimeracateg";
                    }
            }
                
            if(count($venta['Bienesdeuso'])==0){
                if($this->request->is('ajax')){
                    $this->layout = 'ajax';
                }
                $this->set('error','Atencion: 
                   Se cargo una Venta como Bien de Uso y no se relaciono un bien de uso a la venta.</br> Por favor cargue el bien de uso y vuelva a
                   intentar Contabilizar.');
                return;
            }
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
        $asientoestandares = $this->Asientoestandare->find('all', $options);

        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentas=false;
        foreach ($asientoestandares as $asientoestandar) {
            if(count($asientoestandar['Cuenta']['Cuentascliente'])==0){
                //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                //Pero solo si es de la 1ra , 2da o 3ra categoria        
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
         $optionsCosto = array(
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
                'Asientoestandare.tipoasiento'=> $tiposDeAsientosCosto,
            ),
        );
        $asientoestandarescosto = $this->Asientoestandare->find('all', $optionsCosto);

        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentascosto=false;
        foreach ($asientoestandarescosto as $asientoestandarcosto) {
            if(count($asientoestandarcosto['Cuenta']['Cuentascliente'])==0){
                //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                //Pero solo si es de la 1ra , 2da o 3ra categoria        
                    $this->Cuentascliente->create();
                    $this->Cuentascliente->set('cliente_id',$cliid);
                    $this->Cuentascliente->set('cuenta_id',$asientoestandarcosto['Cuenta']['id']);
                    $this->Cuentascliente->set('nombre',$asientoestandarcosto['Cuenta']['nombre']);
                    $this->Cuentascliente->save();
                    $secrearoncuentascosto=true;
            }
        }
        if($secrearoncuentascosto){
            $asientoestandarescosto = $this->Asientoestandare->find('all', $optionsCosto);
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
        $optionsasientocosto = [
            'contain'=>[
                'Movimiento',
            ],
            'conditions' => [
                'Asiento.tipoasiento'=> 'costo',
                'Asiento.periodo'=>$periodo,
                'Asiento.cliente_id'=>$cliid
            ],
        ];
        $asientoyacargadocosto = $this->Asiento->find('first', $optionsasientocosto);
        //Vamos a agrupar y sumar las ventas gravadas y las no gravadas
        $conditionsVentaGravada = [
            'contain'=>[
                'Comprobante'=>[
                    'fields'=>[
                        'tipodebitoasociado'
                    ]
                ],
                'Actividadcliente'=>[
                    'Cuentasganancia'=>['Cuentascliente']
                ],
            ],
            'fields'=>[
                'Venta.comprobante_id',
                'Venta.actividadcliente_id',
                'Venta.tipogasto_id',
                'sum(total) as total,sum(neto) as neto,sum(iva) as iva,
                sum(nogravados) as nogravados, sum(excentos) as exentos, sum(ivapercep) as ivapercep, 
                sum(iibbpercep) as iibbpercep,sum(actvspercep) as actvspercep,sum(impinternos) as impinternos'
            ],
            'group'=>
                ['Venta.comprobante_id','Venta.actividadcliente_id','Venta.tipogasto_id',],
            'conditions'=>[
                'Venta.cliente_id'=>$cliid,
                'Venta.periodo'=>$periodo,
            ],
        ];
        $ventasgravadas = $this->Venta->find('all',$conditionsVentaGravada);

        $impuestosactivos = $this->Cliente->impuestosActivados($cliid,$periodo);
        $ingresosBienDeUso = $this->Tipogasto->ingresosBienDeUso;
        $this->set(compact('cliid','cliente','periodo','pagaCategoria','asientoestandares','asientoestandarescosto','cuentasclientes'
            ,'asientoyacargado','asientoyacargadocosto','ventasgravadas','ventasbiendeuso','impuestosactivos','ingresosBienDeUso'));
        
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
                    'Actividade',
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
        $costoCategoria[] = 'costos';
        $pagaCategoria = [];
        $cuentaclienteaseleccionar=[];
        $tieneGananciasConfigurado = 1;
        $ActividadesGeneros = [];
        foreach ($cliente['Actividadcliente'] as $actividadcliente){
            if(count($actividadcliente['Cuentasganancia'])>0){
                $categoriaActividad = $actividadcliente['Cuentasganancia'][0]['categoria'];
                if(!in_array($categoriaActividad,$tiposDeAsientos)){
                    $tiposDeAsientos[] = "compra".$categoriaActividad;
                    $pagaCategoria[]   = "compra".$categoriaActividad;
                    $costoCategoria[]   = "costocompra".$categoriaActividad;
                }
            }else{
                $tieneGananciasConfigurado *= 0;
            }
            $ActividadesGeneros[$actividadcliente['actividade_id']] = [$actividadcliente['Actividade']['generomonotributo']];
        }
        if($tieneGananciasConfigurado==0){
            if($this->request->is('ajax')){
                $this->layout = 'ajax';
            }
            $this->set('error','Atencion: 
            No se configuro el impuesto Ganancias en el organismo AFIP, configurelo y vuelva a intentar 
            contabilizar');
            return;
        }
        //vamos a revisar que todas las compras del tipo Bien de uso tengan configurado su bien de uso
        $optionCompras=[
            'contain'=>[
                'Bienesdeuso'=>[
                    'Cuentaclientevalororigen',
                    'Cuentaclienteactualizacion',
                    'Cuentaclienteterreno',
                    'Cuentaclienteedificacion',
                    'Cuentaclientemejora',
                ],

            ],
            'conditions'=>[
                'Compra.imputacion'=>'Bs Uso',
                'Compra.periodo'=>$periodo,
                'Compra.cliente_id'=>$cliid
            ]
        ];
        $comprasbiendeuso = $this->Compra->find('all',$optionCompras);
        foreach ($comprasbiendeuso as $compra){
            if(count($compra['Bienesdeuso'])==0){
                if($this->request->is('ajax')){
                    $this->layout = 'ajax';
                }
                $this->set('error','Atencion: 
                   Se cargo una Compra como Bien de Uso y no se cargaron los datos del mismo. Por favor completelos y vuelva a
                   intentar Contabilizar.');
                    return;
            }
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
        $optionsCosto = array(
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
                'Asientoestandare.tipoasiento'=> $costoCategoria,
            ),
        );
       
        $asientoestandaresCosto = $this->Asientoestandare->find('all', $optionsCosto);
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
        $secrearoncuentas=false;
        foreach ($asientoestandaresCosto as $asientoestandar) {
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
            $asientoestandaresCosto = $this->Asientoestandare->find('all', $optionsCosto);
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
        $options = array(
            'contain'=>array(
                'Movimiento',
            ),
            'conditions' => array(
                'Asiento.tipoasiento'=> 'costoscompra',
                'Asiento.periodo'=>$periodo,
                'Asiento.cliente_id'=>$cliid
            ),
        );
        $asientoyacargadoCosto = $this->Asiento->find('first', $options);

        //Vamos a agrupar y sumar las compras gravadas y las no gravadas
        $conditionsCompraGravada = [
            'contain'=>[
                'Actividadcliente'=>[
                    //'Actividade',
                    'Cuentasganancia'=>['Cuentascliente'],
                    'fields'=>['id','actividade_id']
                ],
            ],
            'fields'=>[
                'Compra.tipogasto_id','Compra.tipocredito','Compra.imputacion','Compra.actividadcliente_id','sum(total) as total,
                sum(neto) as neto,sum(iva) as iva,sum(nogravados) as nogravados, sum(exentos) as exentos, 
                sum(ivapercep) as ivapercep,sum(iibbpercep) as iibbpercep,sum(actvspercep) as actvspercep,sum(ganapercep) as ganapercep,
                sum(impinternos) as impinternos,sum(impcombustible) as impcombustible'
            ],
            'group'=>
                ['Compra.tipocredito','Compra.imputacion','Compra.actividadcliente_id','Compra.tipogasto_id'],
            'conditions'=>[
                'Compra.cliente_id'=>$cliid,
                'Compra.periodo'=>$periodo,
            ],
        ];
        $comprasgravadas = $this->Compra->find('all',$conditionsCompraGravada);
        $this->set(compact('cliid','cliente','periodo','pagaCategoria',
                'asientoestandares','asientoestandaresCosto','cuentasclientes',
                'asientoyacargado','asientoyacargadoCosto','comprasgravadas',
                'comprasbiendeuso','ActividadesGeneros'));

        if($this->request->is('ajax')){
            $this->layout = 'ajax';
        }
        return;
    }
    public function contabilizaramortizacion($cliid = null, $periodo = null){
        $this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Asientoestandare');
        $this->loadModel('Compra');
        $this->loadModel('Bienesdeuso');
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
        //$tiposDeAsientos[] = 'compras';
        $pagaCategoria = [];
        $cuentaclienteaseleccionar=[];
        $tieneGananciasConfigurado = 1;
        foreach ($cliente['Actividadcliente'] as $actividadcliente){
            if(count($actividadcliente['Cuentasganancia'])>0){
                $categoriaActividad = $actividadcliente['Cuentasganancia'][0]['categoria'];
                if(!in_array($categoriaActividad,$tiposDeAsientos)){
                    $tiposDeAsientos[] = "amortizacion".$categoriaActividad;
                    $pagaCategoria[]   = "amortizacion".$categoriaActividad;
                }
            }else{
                $tieneGananciasConfigurado *= 0;
            }
        }
        if($tieneGananciasConfigurado==0){
            if($this->request->is('ajax')){
                $this->layout = 'ajax';
            }
            $this->set('error','Atencion: 
            No se configuro el impuesto Ganancias en el organismo AFIP, configurelo y vuelva a intentar 
            contabilizar');
            return;
        }
        //vamos a revisar que todas las compras del tipo Bien de uso tengan configurado su bien de uso
        // tienen que ser las compras con bienes de usos que no se hayan vendido ya
        $pemes = substr($periodo, 0, 2);
        $peanio = substr($periodo, 3);
        $fechadeconsulta = date('Y/m/d',strtotime("01-".$pemes."-".$peanio));

        if(!isset($cliente['Cliente']['fchcorteejerciciofiscal'])||is_null($cliente['Cliente']['fchcorteejerciciofiscal'])||$cliente['Cliente']['fchcorteejerciciofiscal']==""){
            $this->Session->setFlash(__('No se ha configurado fecha decorte de ejercicio fiscal para este
             ccontribuyente .'));
            $fechadecorteAñoActual = date('Y/m/d',strtotime("01-01-".$peanio));
        }else{
            $fechadecorteAñoActual = date('Y/m/d',strtotime($cliente['Cliente']['fchcorteejerciciofiscal']."-".$peanio));
        }
        $fechaInicioConsultaAnio = "";
        $fechaInicioConsultaMes = "";
        $fechaFinConsulta = "";
        if($fechadeconsulta<$fechadecorteAñoActual){
            $fechaInicioConsultaAnio =  date('Y',strtotime($fechadecorteAñoActual." - 1 Years + 1 days"));
            $fechaInicioConsultaMes = date('m', strtotime($fechadecorteAñoActual . " + 1 days"));;
//          $fechaFinConsulta =  $fechadecorteAñoActual;
        }else {
            $fechaInicioConsultaAnio =  date('Y',strtotime($fechadecorteAñoActual." - 1 Years + 1 days"));
            $fechaInicioConsultaMes = date('m', strtotime($fechadecorteAñoActual . " + 1 days"));;
//          $fechaFinConsulta = date('Y/m/d', strtotime($fechadecorteAñoActual . " + 1 Years"));
        }


        $optionCompras=[
            'contain'=>[
                'Cuentaclientevalororigen'=>[
                    'fields'=>[
                        ''
                    ]
                ],
                'Cuentaclienteactualizacion'=>[
                    'fields'=>[
                        ''
                    ]
                ],
                'Cuentaclienteterreno'=>[
                    'fields'=>[
                        ''
                    ]
                ],
                'Cuentaclienteedificacion'=>[
                    'fields'=>[
                        ''
                    ]
                ],
                'Cuentaclientemejora'=>[
                    'fields'=>[
                        ''
                    ]
                ],                
                'Amortizacione'
            ],
            'conditions'=>[               
                'Bienesdeuso.cliente_id'=>$cliid,
            ]
        ];
        $bienesdeusos = $this->Bienesdeuso->find('all',$optionCompras);
        
        $optionCompras=[
            'contain'=>[
                'Actividadcliente'=>[
                    'Cuentasganancia'=>['Cuentascliente']
                ],
                'Bienesdeuso'=>[
                    'Cuentaclientevalororigen',
                    'Cuentaclienteactualizacion',
                    'Cuentaclienteterreno',
                    'Cuentaclienteedificacion',
                    'Cuentaclientemejora',
                    'Amortizacione',
                    'conditions'=>[
                            //'Bienesdeuso.venta_id IN '=>[null,"",0]
                        ]
                ],
            ],
            'conditions'=>[
                'Compra.imputacion'=>'Bs Uso',
                [
                    'OR'=>array(//es <= que el periodo q consultamos
                        'SUBSTRING(Compra.periodo,4,7)*1 < '.$peanio.'*1',
                        'AND'=>array(
                            'SUBSTRING(Compra.periodo,4,7)*1 <= '.$peanio.'*1',
                            'SUBSTRING(Compra.periodo,1,2) <= '.$pemes.'*1'
                        ),
                     ),                    
                    ],
                [
                    'OR'=>array(//es >= que el periodo inicio de periodo fiscal
                        'SUBSTRING(Compra.periodo,4,7)*1 > '.$fechaInicioConsultaAnio.'*1',
                        'AND'=>array(
                            'SUBSTRING(Compra.periodo,4,7)*1 >= '.$fechaInicioConsultaAnio.'*1',
                            'SUBSTRING(Compra.periodo,1,2) >= '.$fechaInicioConsultaMes.'*1'
                        ),
                     ),
                ],
                'Compra.cliente_id'=>$cliid,
            ]
        ];
        $comprasbiendeuso = $this->Compra->find('all',$optionCompras);
        foreach ($comprasbiendeuso as $compra){
            if(count($compra['Bienesdeuso'])==0){
                if($this->request->is('ajax')){
                    $this->layout = 'ajax';
                }
                $this->set('error','Atencion: 
                   Se cargo la Compra '.$compra['Compra']['puntosdeventa'].'-'.$compra['Compra']['numerocomprobante'].' como Bien de Uso en el periodo '.$compra['Compra']['periodo'].' y no se cargaron los datos del mismo. Por favor completelos y vuelva a
                   intentar Contabilizar.');
                 $this->set(compact('comprasbiendeuso'));
                    return;
            }
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
                'Asiento.tipoasiento'=> 'amortizacion',
                'Asiento.periodo'=>$periodo,
                'Asiento.cliente_id'=>$cliid
            ),
        );
        $asientoyacargado = $this->Asiento->find('first', $options);

        //Vamos a agrupar y sumar las compras gravadas y las no gravadas
       
        $this->set(compact('cliid','cliente','periodo','pagaCategoria','asientoestandares','cuentasclientes','asientoyacargado',
            'comprasgravadas','bienesdeusos'));

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
        $optionsCliente = [
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
        $cliente = $this->Cliente->find('first', $optionsCliente);
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
    public function contabilizarretencionessufridas($cliid = null, $periodo = null){
        $this->loadModel('Cliente');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Asiento');
        $options = [
            'contain'=>[
                'Impcli'=>[
                    'Asiento'=>[
                        'Movimiento'=>[
                            'Cuentascliente'
                        ],
                        'conditions'=>[
                            'Asiento.tipoasiento'=> 'retencionessufridas',
                            'Asiento.periodo'=>$periodo,
                        ]
                    ],
                    'Impuesto'=>[
                        'Asientoestandare'=>[
                            'Cuenta',
                            'conditions'=>[
                                'Asientoestandare.tipoasiento'=>'retencionessufridas'
                            ]
                        ]
                    ],
                    'Conceptosrestante'=>[
                        'conditions'=>[
                            'Conceptosrestante.conceptostipo_id'=>2,
                            'Conceptosrestante.periodo'=>$periodo
                        ]
                    ],
                    'conditions'=>[

                    ]
                ]
            ],
            'conditions' => [
                'Cliente.' . $this->Cliente->primaryKey => $cliid,
            ],
        ];
        $cliente = $this->Cliente->find('first', $options);

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

        $cuentaxcuentaclienteOptions = [
            'conditions' => [
                'Cuentascliente.cliente_id'=> $cliid
            ],
            'fields' => [
                'Cuenta.id',
                'Cuentascliente.id',
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
        $cuentaxcuentacliente=$this->Cuentascliente->find('list',$cuentaxcuentaclienteOptions);

        $options = [
            'contain'=>[
                'Movimiento'=>[
                    'Cuentascliente'
                ],
            ],
            'conditions' => [
                'Asiento.tipoasiento'=> 'retencionessufridas',
                'Asiento.periodo'=>$periodo,
            ],
        ];
        $asientosyacargados = $this->Asiento->find('all', $options);
        $this->set(compact('cliid','cliente','periodo','cuentasclientes','asientosyacargados','cuentaxcuentacliente'));
        $this->layout = 'ajax';
    }
    public function crearapertura($clienteid = null, $periodo = null){
        ini_set('memory_limit', '2560M');
        ini_set('max_execution_time', 2600);
        $this->loadModel('Cliente');
        $this->loadModel('Movimiento');
        $this->loadModel('Cuentascliente');
        $this->loadModel('User');
        $this->loadModel('Asientoestandare');
        $this->loadModel('Asiento');
        $this->loadModel('Cuenta');

        $pemes = substr($periodo, 0, 2);
        $peanio = substr($periodo, 3);

        $optionmiCliente = [
                'contain' => [
                    'Asiento'=>[
                        'conditions'=>[
                            'Asiento.tipoasiento'=>'apertura',
                            'Asiento.periodo'=>$periodo
                        ]
                    ]
                ],
                'conditions' => ['Cliente.id'=>$clienteid]
        ];
        $micliente = $this->Cliente->find('first',$optionmiCliente);
        $optionUser = [
                'contain' => [
                ],
                'conditions' => ['User.id'=>$this->Session->read('Auth.User.id'),]
        ];
        $user = $this->User->find('first',$optionUser);
        $fechadeconsulta = date('Y-m-t',strtotime("01-".$pemes."-".$peanio));

        if(!isset($micliente['Cliente']['fchcorteejerciciofiscal'])||is_null($micliente['Cliente']['fchcorteejerciciofiscal'])||$micliente['Cliente']['fchcorteejerciciofiscal']==""){
            $this->Session->setFlash(__('No se ha configurado fecha decorte de ejercicio fiscal para este
             contribuyente .'));
            $fechadecorteAñoActual = date('Y-m-d',strtotime("01-01-".$peanio));
        }else{
            $fechadecorteAñoActual = date('Y-m-d',strtotime($micliente['Cliente']['fchcorteejerciciofiscal']."-".$peanio));
        }
        $fechaInicioConsulta = "";
        $fechaInicioPeriodoAnterior = "";
        $fechaFinPeriodoAnterior = "";
        //$fechaFinConsulta = "";
        if($fechadeconsulta<=$fechadecorteAñoActual){
            $fechaInicioConsulta =  date('Y-m-d',strtotime($fechadecorteAñoActual." - 1 Years + 1 days"));
            $fechaInicioPeriodoAnterior =  date('Y-m-d',strtotime($fechadecorteAñoActual." - 2 Years + 1 days"));
            
            //$fechaFinConsulta =  $fechadecorteAñoActual;
        }else {
            $fechaInicioConsulta = date('Y-m-d', strtotime($fechadecorteAñoActual . " + 1 days"));;
            $fechaInicioPeriodoAnterior =  date('Y-m-d',strtotime($fechadecorteAñoActual." - 1 Years + 1 days"));
            //$fechaFinConsulta = date('Y/m/d', strtotime($fechadecorteAñoActual . " + 1 Years"));
        }
        $fechaFinPeriodoAnterior =  date('Y-m-d',strtotime($fechaInicioPeriodoAnterior." + 1 Years - 1 days"));
        //la fecha fin consulta es esta por que solo vamos a ver hasta el ultimo dia del periodo que estamos
        // consultando
        $fechaFinConsulta =  date('Y-m-t',strtotime($fechadeconsulta));

        //vamos a intercambiar las fechas por que quiero traer los datos del periodo anterior
        $this->set('fechaInicioConsulta',$fechaInicioPeriodoAnterior);
        $this->set('fechaFinConsulta',$fechaFinPeriodoAnterior);

        $optionCliente = [
            'contain' => [
                'Cuenta',
                'Movimiento'=>[
                        'Asiento'=>[
                                'fields'=>['id','fecha','tipoasiento']
                        ],
                        'conditions'=>[
                                "Movimiento.asiento_id IN (
                                        SELECT id FROM asientos as Asiento 
                                        WHERE Asiento.cliente_id = ".$clienteid."
                                        AND    Asiento.fecha  >= '".$fechaInicioPeriodoAnterior."'
                                        AND    Asiento.fecha  <= '".$fechaFinPeriodoAnterior."'
                                )"
                        ]
                ],
            ],
            'conditions' => ['Cuentascliente.cliente_id'=>$clienteid],
            'order'=>[
                'Cuenta.numero'
            ]
        ];
        $cuentasclientes = $this->Cuentascliente->find('all',$optionCliente);
        $cuentaclienteOptions = [
            'conditions' => [
                'Cuentascliente.cliente_id'=> $clienteid
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
        $allcuentasclientes=$this->Cuentascliente->find('list',$cuentaclienteOptions);            
          //vamos a traer las cuentas que tienen que aparecer en el asiento de apertura
        //ya sea que tienen valor o no
        $optionCuenta = [
            'contain' => [

            ],
            'conditions' => [
                'OR'=>[
                    "Cuenta.numero like '11050%'",
                    "Cuenta.numero like '420300001'"
                    ]
                ],
        ];
        $cuentasdeapertura = $this->Cuenta->find('list',$optionCuenta);
        $this->set('cuentasdeapertura',$cuentasdeapertura);
        
        $this->set('cuentasclientes',$cuentasclientes);
        $this->set('allcuentasclientes',$allcuentasclientes);
        $this->set('cliente',$micliente);
        $this->set('user',$user);
        $this->set('periodo',$periodo);
        
    }
    public function contabilizarapertura($clienteid = null, $periodo = null){
        ini_set('memory_limit', '2560M');
        $this->loadModel('Cliente');
        $this->loadModel('Movimiento');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Asientoestandare');

        $pemes = substr($periodo, 0, 2);
        $peanio = substr($periodo, 3);

        $optionmiCliente = [
                'contain' => [
                ],
                'conditions' => ['Cliente.id'=>$clienteid]
        ];
        $micliente = $this->Cliente->find('first',$optionmiCliente);

        $fechadeconsulta = date('Y/m/d',strtotime("01-".$pemes."-".$peanio));

        if(!isset($micliente['Cliente']['fchcorteejerciciofiscal'])||is_null($micliente['Cliente']['fchcorteejerciciofiscal'])||$micliente['Cliente']['fchcorteejerciciofiscal']==""){
                $this->Session->setFlash(__('No se ha configurado fecha decorte de ejercicio fiscal para este contribuyente.'));
                $fechadecorteAñoActual = date('Y/m/d',strtotime("01-01-".$peanio));
        }else{
                $fechadecorteAñoActual = date('Y/m/d',strtotime($micliente['Cliente']['fchcorteejerciciofiscal']."-".$peanio));
        }
        $fechaInicioConsulta = "";
        $anioInicioConsulta = "";
        $fechaFinConsulta = "";
        if($fechadeconsulta<$fechadecorteAñoActual){
                $fechaInicioConsulta =  date('Y/m/d',strtotime($fechadecorteAñoActual." - 1 Years + 1 days"));
                $anioInicioConsulta =  date('Y',strtotime($fechadecorteAñoActual." - 1 Years + 1 days"));
        }else {
                $fechaInicioConsulta = date('Y/m/d', strtotime($fechadecorteAñoActual . " + 1 days"));;
                $anioInicioConsulta = date('Y', strtotime($fechadecorteAñoActual . " + 1 days"));;
        }
        //la fecha fin consulta es esta por quesolo vamos a ver hasta el ultimo dia del periodo que estamos
        // consultando
        $fechaFinConsulta =  date('Y/m/t',strtotime($fechadeconsulta));
        $this->set('fechaInicioConsulta',$fechaInicioConsulta);
        $this->set('fechaFinConsulta',$fechaFinConsulta);

        $optionCliente = [
                'contain' => [
                        'Asiento'=>[
                            'Movimiento'=>[
                                'Cuentascliente'
                            ],
                            'conditions'=>[
                                'Asiento.tipoasiento'=>'apertura'
                            ]
                        ],
                ],
                'conditions' => ['Cliente.id'=>$clienteid]
        ];

        $cliente = $this->Cliente->find('first',$optionCliente);
        //aca vamos a setiar las cosas que necesitamos para dar de alta un asiento
        $cuentaclienteOptions = [
                'conditions' => [
                        'Cuentascliente.cliente_id'=> $clienteid
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
        $this->set('cliente',$cliente);
        $this->set('periodo',$periodo);
                
        $options = array(
            'contain'=>array(
                'Movimiento'=>[
                    'Cuentascliente'
                ],
            ),
            'conditions' => array(
                'Asiento.tipoasiento'=> 'Apertura',
                'SUBSTRING(Asiento.fecha,1,4)'=>$anioInicioConsulta,
                'Asiento.cliente_id'=>$clienteid
            ),
        );
        $asientoyacargado = $this->Asiento->find('first', $options);
        $this->set('asientoyacargado',$asientoyacargado);
        $this->layout = 'ajax';

    }            
    public function contabilizarexistenciafinal($clienteid = null, $periodo = null){
        ini_set('memory_limit', '2560M');
        $this->loadModel('Cliente');
        $this->loadModel('Movimiento');
        $this->loadModel('Cuentascliente');
        $this->loadModel('Asientoestandare');

        $pemes = substr($periodo, 0, 2);
        $peanio = substr($periodo, 3);

        $optionmiCliente = [
                'contain' => [
                ],
                'conditions' => ['Cliente.id'=>$clienteid]
        ];
        $micliente = $this->Cliente->find('first',$optionmiCliente);

        $fechadeconsulta = date('Y/m/d',strtotime("01-".$pemes."-".$peanio));

        if(!isset($micliente['Cliente']['fchcorteejerciciofiscal'])||is_null($micliente['Cliente']['fchcorteejerciciofiscal'])||$micliente['Cliente']['fchcorteejerciciofiscal']==""){
                $this->Session->setFlash(__('No se ha configurado fecha decorte de ejercicio fiscal para este contribuyente.'));
                $fechadecorteAñoActual = date('Y/m/d',strtotime("01-01-".$peanio));
        }else{
                $fechadecorteAñoActual = date('Y/m/d',strtotime($micliente['Cliente']['fchcorteejerciciofiscal']."-".$peanio));
        }
        $fechaInicioConsulta = "";
        $fechaFinConsulta = "";
        if($fechadeconsulta<$fechadecorteAñoActual){
                $fechaInicioConsulta =  date('Y/m/d',strtotime($fechadecorteAñoActual." - 1 Years + 1 days"));
        }else {
                $fechaInicioConsulta = date('Y/m/d', strtotime($fechadecorteAñoActual . " + 1 days"));;
        }
        //la fecha fin consulta es esta por quesolo vamos a ver hasta el ultimo dia del periodo que estamos
        // consultando
        $fechaFinConsulta =  date('Y/m/t',strtotime($fechadeconsulta));
        $this->set('fechaInicioConsulta',$fechaInicioConsulta);
        $this->set('fechaFinConsulta',$fechaFinConsulta);

        $optionCliente = [
            'contain' => [
                'Asiento'=>[
                    'Movimiento'=>[
                        'Cuentascliente'
                    ],
                    'conditions'=>[
                        'Asiento.tipoasiento'=>'apertura',
                        "Asiento.fecha >= '".date('Y-m-d', strtotime($fechaInicioConsulta))."'",
                        "Asiento.fecha <= '".date('Y-m-d', strtotime($fechaFinConsulta))."'"
                    ]
                ],
            ],
            'conditions' => ['Cliente.id'=>$clienteid]
        ];

        $cliente = $this->Cliente->find('first',$optionCliente);
        //aca vamos a setiar las cosas que necesitamos para dar de alta un asiento
        $cuentaclienteOptions = [
                'conditions' => [
                        'Cuentascliente.cliente_id'=> $clienteid
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
        $this->set('cliente',$cliente);
        $this->set('periodo',$periodo);
        
        //seccion del asiento en si
        $options = array(
            'contain'=>array(
                'Cuenta'=>[
                    'Cuentascliente'=>[
                        'Cuenta',
                        'conditions'=>[
                            'Cuentascliente.cliente_id'=>$clienteid
                        ]
                    ]
                ]
            ),
            'conditions' => array(
                'Asientoestandare.tipoasiento'=> 'existenciafinal',
            ),
        );       
        $asientoestandares = $this->Asientoestandare->find('all', $options);
        /*vamos a controlar que el asiento estandar tenga sus cuentas por defecto creadas en cuentacliente*/
        $secrearoncuentas=false;
        foreach ($asientoestandares as $asientoestandar) {
            if(count($asientoestandar['Cuenta']['Cuentascliente'])==0){
                //este asiento estandar carece de esta cuenta para este cliente por lo que hay que agregarla
                $this->Cuentascliente->create();
                $this->Cuentascliente->set('cliente_id',$clienteid);
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
        $options = array(
            'contain'=>array(
                'Movimiento',
            ),
            'conditions' => array(
                'Asiento.tipoasiento'=> 'Existencia Final',
                'Asiento.periodo'=>$periodo,
                'Asiento.cliente_id'=>$clienteid
            ),
        );
        $asientoyacargado = $this->Asiento->find('first', $options);
        $this->set('asientoestandares',$asientoestandares);
        $this->set('asientoyacargado',$asientoyacargado);
        $this->layout = 'ajax';

    }            
//    public function numerarasientos(){
//
//        ini_set('max_execution_time', 600);
//        $optionsAsientos = array(
//            'contain'=>[],
//            'conditions' => array(
//            ),
//            'order'=>[
//                'Asiento.cliente_id',
//                'Asiento.id'
//            ]
//        );
//        $asientos = $this->Asiento->find('all', $optionsAsientos);
//        $numeroAsiento = 1;
//        $clienteActual = $asientos[0]['Asiento']['cliente_id'];
//        $respuesta = [];
//        foreach ($asientos as $asiento) {
//            $clientenuevo = $asiento['Asiento']['cliente_id'];
//            if($clienteActual!=$clientenuevo){
//                $numeroAsiento=1;
//                $clienteActual = $clientenuevo;
//            }
//            $this->Asiento->read(null, $asiento['Asiento']['id']);
//            $this->Asiento->set('numero',$numeroAsiento);
//            if(!isset($respuesta[$asiento['Asiento']['cliente_id']]))
//                $respuesta[$asiento['Asiento']['cliente_id']]=[];
//            if($this->Asiento->save()){
//                $respuesta[$asiento['Asiento']['cliente_id']][]=$asiento['Asiento']['id']."_".$numeroAsiento;
//            }else{
//                $respuesta[$asiento['Asiento']['cliente_id']][]=$asiento['Asiento']['id']."_".$numeroAsiento."ERROR";
//            }
//            $numeroAsiento ++;
//        }
//        $this->set('data',$respuesta);
//        $this->autoRender=false;
//        $this->layout = 'ajax';
//        $this->render('serializejson');
//    }
}
