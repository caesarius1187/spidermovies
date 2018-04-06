<?php
App::uses('AppController', 'Controller');
/**
 * Empleados Controller
 *
 * @property Empleado $Empleado
 * @property PaginatorComponent $Paginator
 */
class EmpleadosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','RequestHandler');
	public function index() {
		$this->Empleado->recursive = 0;
		$this->set('empleados', $this->Paginator->paginate());
	}
	public function cargar($id=null,$periodo=null){
		$this->loadModel('Cliente');
		$this->loadModel('Impcli');
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
		$this->set('periodo',$periodo);
		$cliente=$this->Cliente->find('first', array(
				'contain'=>array(
					'Sueldo'=>array(
						'conditions' => array(
							'Sueldo.periodo'=>$periodo
						),
					),
					'Empleado'=>array(
						'Valorrecibo'=>array(
							'conditions'=>array(
								'Valorrecibo.periodo'=>$periodo,
							),
							'fields'=>array('Distinct(Valorrecibo.tipoliquidacion)'),
						),
						'conditions'=>array(
							'OR'=>[
								'Empleado.fechaegreso >= ' => date('Y-m-d',strtotime("01-".$periodo)),
								'Empleado.fechaegreso is null' ,
							],
							'Empleado.fechaingreso <= '=>date('Y-m-d',strtotime("28-".$periodo)),
						),
						'order'=>[
							'Empleado.legajo*1'
						],
					),
					'Impcli'=>[
						'Impuesto',
						'Periodosactivo'=>[
							'conditions'=>$conditionsImpCliHabilitados
						]
					]
				),
				'conditions' => array(
					'id' => $id,
				),
			)
		);
		/*AFIP*/
		$tieneMonotributo=False;
		$tieneIVA=False;
		$tieneIVAPercepciones=False;
		$tieneImpuestoInterno=False;
		/*DGR*/
		$tieneAgenteDePercepcionIIBB=False;
		/*DGRM*/
		$tieneAgenteDePercepcionActividadesVarias=False;
		foreach ($cliente['Impcli'] as $impcli) {
			/*AFIP*/
			if($impcli['impuesto_id']==4/*Monotributo*/){
				//Tiene Monotributo asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que es un Monotributista Activo en este periodo
					//Tenemos que asegurarnos que no existan periodos activos que coincidan entre Monotributo e IVA
					$tieneMonotributo=True;
					$tieneIVA=False;
				}
			}
			if($impcli['impuesto_id']==19/*IVA*/){
				//Tiene IVA asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que es un Responsable Inscripto Activo en este periodo
					//Tenemos que asegurarnos que no existan periodos activos que coincidan entre Monotributo e IVA
					$tieneMonotributo=False;
					$tieneIVA=True;
				}
			}
			if($impcli['impuesto_id']==184/*IVA Percepciones*/){
				//Tiene IVA Percepciones asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que tiene IVA Percepciones Activo en este periodo
					$tieneIVAPercepciones=True;
				}
			}
			if($impcli['impuesto_id']==185/*Impuesto Interno*/){
				//Tiene Impuesto Interno asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que tiene Impuesto Interno Activo en este periodo
					$tieneImpuestoInterno=True;
				}
			}
			/*DGR*/
			if($impcli['impuesto_id']==173/*Agente de Percepcion IIBB*/){
				//Tiene Agente de Percepcion IIBB asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que tiene Agente de Percepcion IIBB Activo en este periodo
					$tieneAgenteDePercepcionIIBB=True;
				}
			}
			/*DGRM*/
			if($impcli['impuesto_id']==186/*Agente de Percepcion de Actividades Varias*/){
				//Tiene Agente de Percepcion IIBB asignado pero hay que ver si tiene periodos activos
				if(Count($impcli['Periodosactivo'])!=0){
					//Aca estamos Seguros que tiene Agente de Percepcion de Actividades Varias Activo en este periodo
					$tieneAgenteDePercepcionActividadesVarias=True;
				}
			}
		}
		$cliente['Cliente']['tieneMonotributo'] = $tieneMonotributo;
		$cliente['Cliente']['tieneIVA'] = $tieneIVA;
		$cliente['Cliente']['tieneIVAPercepciones'] = $tieneIVAPercepciones;
		$cliente['Cliente']['tieneImpuestoInterno'] = $tieneImpuestoInterno;
		$cliente['Cliente']['tieneAgenteDePercepcionIIBB'] = $tieneAgenteDePercepcionIIBB;
		$cliente['Cliente']['tieneAgenteDePercepcionActividadesVarias'] = $tieneAgenteDePercepcionActividadesVarias;
		$this->set(compact('cliente'));

		//aca vamos a listar los impclis que tenga activos el cliente pero con el nombre del impuesto
		$conditionsImpCliHabilitadosImpuestos = array(
			//El periodo esta dentro de un desde hasta
			'AND'=> array(
				'Periodosactivo.impcli_id = Impcli.id',
				$esMayorQueDesde,
				'OR'=> array(
					$esMenorQueHasta,
					$periodoNull
				)
			)

		);
		$clienteImpuestosOptions = array(
			'conditions' => array(
				'Impcli.cliente_id'=> $id
			),
			'fields'=>array('Impcli.id','Impuesto.nombre'),
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
		$this->set('impclis', $impclis);

		$conditionsCli = array(
			'Grupocliente',
		);
		$lclis = $this->Cliente->find('list',array(
			'contain' =>$conditionsCli,
			'conditions' => array(
				'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id')
			),
			'order' => array(
				'Grupocliente.nombre','Cliente.nombre'
			),
		));
		$this->set(compact('lclis'));
	}
	public function cargamasiva($id=null,$periodo=null,$convenio=null,$tipoliquidacion=null){
		$this->loadModel('Cliente');
		$this->loadModel('Impcli');
		//A: Es menor que periodo Hasta
		$this->set('periodo',$periodo);
		$this->set('tipoliquidacion',$tipoliquidacion);
		$this->set('convenio',$convenio);

		//vamos a mandar los convenios
		$cliente=$this->Cliente->find('first', array(
				'contain'=>array(
					'Empleado'=>array(
						'Conveniocolectivotrabajo',
						'Valorrecibo'=>array(
							'conditions'=>array(
								'Valorrecibo.periodo'=>$periodo,
							),
							'fields'=>array('Distinct(Valorrecibo.tipoliquidacion)'),
						),
						'conditions'=>array(
							'OR'=>[
								'Empleado.fechaegreso >= ' => date('Y-m-d',strtotime("01-".$periodo)),
								'Empleado.fechaegreso is null' ,
							],
							'Empleado.fechaingreso <= '=>date('Y-m-d',strtotime("28-".$periodo)),
						),
						'order'=>[
							'Empleado.legajo*1'
						],
					),
				),
				'conditions' => array(
					'id' => $id,
				),
			)
		);
		$this->set(compact('cliente'));
	}
	public function exportacion($cliid=null,$periodo=null,$tipoliquidacion=null){
            ini_set('memory_limit', '2560M');
            $this->Components->unload('DebugKit.Toolbar');
            $this->loadModel('Empleado');
            $this->loadModel('Cliente');            
            $this->set('periodo',$periodo);
            $this->set('cliid',$cliid);
            $options = array(
                'contain'=>array(
                    'Empleado'=>array(
                        'Conveniocolectivotrabajo'=>[
                            'Impuesto'
                        ],
                        'Valorrecibo'=>array(
                            'Cctxconcepto'=>array(
                                'Concepto',
                                'Conveniocolectivotrabajo'=>[]
                            ),
                            'conditions'=>array(
                                'Valorrecibo.periodo'=>$periodo,
                                'Valorrecibo.tipoliquidacion'=>array(1,2,3,7)
                            )
                        ),
                        'conditions'=>array(
                            'Empleado.conveniocolectivotrabajo_id <> 10',//este convenio no debe impactar en suss por que es
                            //servicio domestico
                            'Empleado.cliente_id' => $impcliSolicitado['Impcli']['cliente_id'],
                            'OR'=>[
                                'Empleado.fechaegreso >= ' => date('Y-m-d',strtotime("01-".$periodo)),
                                'Empleado.fechaegreso is null' ,
                            ],
                            'Empleado.fechaingreso <= '=>date('Y-m-d',strtotime("28-".$periodo)),
                        ),
                        'order'=>['Empleado.cuit']
                    )
                ),
                'conditions' => array('Cliente.' . $this->Cliente->primaryKey => $cliid)
            );

            $cliente = $this->Cliente->find('first', $options);
            $this->set('cliente',$cliente);

            $this->set(compact('cliid','periodo'));           
            $this->set('codigorevista',$this->Empleado->codigorevista);
            $this->set('codigoactividad',$this->Empleado->codigoactividad);
            $this->set('codigomodalidadcontratacion',$this->Empleado->codigomodalidadcontratacion);
            $this->set('codigosiniestrado',$this->Empleado->codigosiniestrado);
            $this->set('tipoempresa',$this->Empleado->tipoempresa);
            $this->set('codigozona',$this->Empleado->codigozona);

            //Aca vamos a buscar si tiene Monotributo
            $pemes = substr($periodo, 0, 2);
            $peanio = substr($periodo, 3);
        }	
    public function view($id = null) {
            if (!$this->Empleado->exists($id)) {
                    throw new NotFoundException(__('Invalid empleado'));
            }
            $options = array('conditions' => array('Empleado.' . $this->Empleado->primaryKey => $id));
            $this->set('empleado', $this->Empleado->find('first', $options));
    }
    public function papeldetrabajosueldos($cliid=null,$periodo=null ,$empleadoamostrar=null, $tipoliquidacion=null) {
            $this->loadModel('Impcli');
            $this->loadModel('Empleado');
            $this->loadModel('Concepto');
            $this->loadModel('Cctxconcepto');
            $this->loadModel('Valorrecibo');
            $this->loadModel('Cargo');
            if (isset($this->request->data['Cctxconcepto'])) {
                $this->loadModel('Cctxconcepto');
                $this->Cctxconcepto->create();
                //lo que vamos a guardar aca es un cctXConcepto personalizado
                //entonces tenemos que buscar el maximo codigo cargado y usar el siguiente
                $optionsConcepto=array(
                        'fields'=>array(
                                'MAX(SUBSTRING(Concepto.codigo,2)) as codigoMax'
                        ),
                        'conditions'=>array(
                                'SUBSTRING(Concepto.codigo,1,1)'=>'P'
                        )
                );
                $maxCodigoConcepto= $this->Concepto->find('all',$optionsConcepto);

                $optionsCctxconcepto=array(
                        'fields'=>array(
                                'MAX(SUBSTRING(Cctxconcepto.codigopersonalizado,2)) as codigoMax'
                        ),
                        'conditions'=>array(
                                'SUBSTRING(Cctxconcepto.codigopersonalizado,1,1)'=>'P'
                        )
                );
                $maxCodigoCctXConcepto= $this->Cctxconcepto->find('all',$optionsCctxconcepto);
                $this->set(compact('maxCodigoConcepto','maxCodigoCctXConcepto'));
                //Debugger::dump($maxCodigoConcepto);
                //Debugger::dump($maxCodigoCctXConcepto);
                $codigoMax = 0;
                if($maxCodigoConcepto[0][0]['codigoMax']*1>$maxCodigoCctXConcepto[0][0]['codigoMax']*1){
                        $codigoMax = $maxCodigoConcepto[0][0]['codigoMax']+1;
                }else{
                        $codigoMax = $maxCodigoCctXConcepto[0][0]['codigoMax']+1;
                }
                $this->request->data['Cctxconcepto']['codigopersonalizado'] = "P".$codigoMax;
                if ($this->Cctxconcepto->save($this->request->data['Cctxconcepto'])) {

                } else {
                        $this->Session->setFlash(__('Error al guardar el concepto para el convenio por favor intente de nuevo mas tarde.'));
                }
            }
            if (isset($this->request->data['Valorrecibo'])) {
                $this->loadModel('Valorrecibo');
                $this->Valorrecibo->create();
                if ($this->Valorrecibo->saveAll($this->request->data['Valorrecibo'])) {

                } else {
                        $this->Session->setFlash(__('Error al guardar el recibo de sueldo por favor intente de nuevo mas tarde.'));
                }
            }
            $this->set(compact('periodo'));
            if(!isset($tipoliquidacion)||$tipoliquidacion==null||$tipoliquidacion==""||$tipoliquidacion=="undefined"){
                $this->Session->setFlash(__('Error 1, No se recibio Tipo de Liquidacion.'));
                return $this->redirect(array('action' => 'cargamasiva',$empleadoamostrar,$periodo,));
            }
            $conditionLiquidacion = [];
            $numeroliquidacion = 1;
            switch ($tipoliquidacion){
                case 'liquidaprimeraquincena':
                        $conditionLiquidacion['Empleado.liquidaprimeraquincena']='1';
                        $numeroliquidacion = 1;
                        break;
                case 'liquidasegundaquincena':
                        $conditionLiquidacion['Empleado.liquidasegundaquincena']='1';
                        $numeroliquidacion = 2;
                        break;
                case 'liquidamensual':
                        $conditionLiquidacion['Empleado.liquidamensual']='1';
                        $numeroliquidacion = 3;
                        break;
                case 'liquidapresupuestoprimera':
                        $conditionLiquidacion['Empleado.liquidapresupuestoprimera']='1';
                        $numeroliquidacion = 4;
                        break;
                case 'liquidapresupuestosegunda':
                        $conditionLiquidacion['Empleado.liquidapresupuestosegunda']='1';
                        $numeroliquidacion = 5;
                        break;
                case 'liquidapresupuestomensual':
                        $conditionLiquidacion['Empleado.liquidapresupuestomensual']='1';
                        $numeroliquidacion = 6;
                        break;
                case 'liquidasac':
                        $conditionLiquidacion['Empleado.liquidasac']='1';
                        $numeroliquidacion = 7;
                        break;
		}
		$optionsempleados = array(
                    'contain'=>array(
                        'Cargo',
                        'Conveniocolectivotrabajo'=>array(
                            'Cctxconcepto'=>array(
                                'Concepto',
                                'Valorrecibo'=>array(
                                    'conditions'=>array(
                                            'Valorrecibo.empleado_id'=>$empleadoamostrar,
                                            'Valorrecibo.tipoliquidacion'=>$numeroliquidacion,
                                            'Valorrecibo.periodo'=>$periodo,
                                    )
                                ),
                                'conditions'=>array(
                                    'OR'=>array(
                                            'AND'=>array(
                                                    'Cctxconcepto.cliente_id' => $cliid,
                                                    'Cctxconcepto.campopersonalizado' => 1,
                                            ),
                                            'Cctxconcepto.campopersonalizado' => 0,
                                    ),
                                ),
                                'order'=>array('Cctxconcepto.orden'),
                            ),
                            'Impuesto'=>[
                                'Impcli'=>[
                                        'conditions'=>[
                                                'Impcli.cliente_id'=>$cliid
                                        ]
                                ]
                            ],
                        ),
                    ),
                    'conditions' => array(
                            'Empleado.' . $this->Empleado->primaryKey => $empleadoamostrar,
                    )
		);
		$optionsempleados['conditions']=[
                    'Empleado.' . $this->Empleado->primaryKey => $empleadoamostrar,
                    $conditionLiquidacion,
		];
		$empleado = $this->Empleado->find('first', $optionsempleados);
		/*
         * Primero vamos a ordenar los cctxconcepto en base a la seccion a la que pertenece
         * */
        $this->set('empleadobeforeorden',$empleado);
        if(isset($empleado['Conveniocolectivotrabajo'])){
			//si el convenio es de UTGHRA entonces tenemos que buscar el valor del basico mas bajo
            $basicoMinimoCargo = 0;
            if($empleado['Conveniocolectivotrabajo']['id']=='7'){
                //Gastronomicos
                $options = [
                    'contain'=>[
                    ],
                    'conditions' => array('Cargo.id' => '169')/*Categoria 1 1 Estrella*/
                ];
                $cargoMinimo = $this->Cargo->find('first', $options);
                $basicoMinimoCargo = $cargoMinimo['Cargo']['sueldobasico'];
            }
            if($empleado['Conveniocolectivotrabajo']['id']=='8'){
                //CACYR
                $options = [
                    'contain'=>[
                    ],
                    'conditions' => array('Cargo.id' => '77')/*Categoria 1*/
                ];
                $cargoMinimo = $this->Cargo->find('first', $options);
                $basicoMinimoCargo = $cargoMinimo['Cargo']['sueldobasico'];
            }
			if($empleado['Conveniocolectivotrabajo']['id']=='13'){
                //UTEDYC
                $options = [
                    'contain'=>[
                    ],
                    'conditions' => array('Cargo.id' => '195')/*Administrativo de segunda*/
                ];
                $cargoMinimo = $this->Cargo->find('first', $options);
                $basicoMinimoCargo = $cargoMinimo['Cargo']['sueldobasico'];
            }
            $this->set(compact('basicoMinimoCargo'));
            //pregunto si esta definido por que puede pasar que no se hayan encontrado empleados que liquiden con esta
            // configuracion
            for ($i=0;$i<count($empleado['Conveniocolectivotrabajo']['Cctxconcepto'])-1;$i++){
                for ($j=$i;$j<count($empleado['Conveniocolectivotrabajo']['Cctxconcepto']);$j++) {
                    if(!isset($empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['Concepto'])){
                        $seccionburbuja = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['seccionpersonalizada'];
                        $seccionaux = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]['seccionpersonalizada'];
                    }else {
                        if(!isset($empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['Concepto']['seccion'])){
                            Debugger::dump($empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['Concepto']);
                        }
                        $seccionburbuja = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['Concepto']['seccion'];
                        $seccionaux = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]['Concepto']['seccion'];
                    }
                    $burbuja=0;
                    $aux=0;
                    switch ($seccionburbuja){
                        case 'Datos del Empleado':
                            $burbuja=1;
                            break;
                        case 'REMUNERATIVOS':
                            $burbuja=2;
                            break;
                        case 'NO REMUNERATIVOS':
                            $burbuja=3;
                            break;
                        case 'APORTES':
                            $burbuja=4;
                            break;
                        case 'TOTALES':
                            $burbuja=5;
                            break;
                        case 'OBRA SOCIAL':
                            $burbuja=6;
                            break;
                    }
                    switch ($seccionaux){
                        case 'Datos del Empleado':
                            $aux=1;
                            break;
                        case 'REMUNERATIVOS':
                            $aux=2;
                            break;
                        case 'NO REMUNERATIVOS':
                            $aux=3;
                            break;
                        case 'APORTES':
                            $aux=4;
                            break;
                        case 'TOTALES':
                            $aux=5;
                            break;
                        case 'OBRA SOCIAL':
                            $aux=6;
                            break;
                    }
                    if($burbuja>$aux){
                        $myaux=$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i];
                        $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]=$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j];
                        $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]=$myaux;
                    }
                }
            }
            //ahora vamos a acomodar por cctxconcepto ORDEN
            for ($i=0;$i<count($empleado['Conveniocolectivotrabajo']['Cctxconcepto'])-1;$i++){
                for ($j=$i;$j<count($empleado['Conveniocolectivotrabajo']['Cctxconcepto']);$j++) {
                    //si son de la misma seccion comparo sino no
                    $seccionburbuja = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['Concepto']['seccion'];
                    $seccionaux = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]['Concepto']['seccion'];
                    if($seccionburbuja!=$seccionaux){
                        continue;
                    }
                    $ordenburbuja = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['orden'];
                    $ordenaux = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]['orden'];
                    if($ordenburbuja>$ordenaux){
                        $myaux=$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i];
                        $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]=$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j];
                        $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]=$myaux;
                    }
                }
            }
        }
        //Resulta que para calcular el SAC tenemos que buscar de los ultimos 6 meses un par de datos
        //como Total Remunerativos y Total SD Excepto Indemnizatorios, tengo que buscar el maximo de ellos
        //y devolverlo en el empleado
        $mayorRemunerativo = 0;
        $mayorNORemunerativo = 0;
        for ($m=1;$m<=5;$m++){
            $timePeriodo = strtotime("01-".$periodo ." -".$m." months");
            $periodoPrevio[] = date("m-Y",$timePeriodo);
        }
        $optionsValorecibo = array(
            'contains'=>[
                    'Cctxconcepto'
            ],
            'joins'=>[
                [
                    'table'=>'conceptos',
                    'alias' => 'Concepto',
                    'type'=>'inner',
                    'conditions'=> [
                        'Cctxconcepto.concepto_id = Concepto.id',
                        'Concepto.id'=>['27','103'],
                    ]
                ],
            ],
            'conditions' => array(
                'Valorrecibo.periodo' => $periodoPrevio,
                'Valorrecibo.empleado_id' => $empleadoamostrar,
            ),
        );
        $valorRecibos = $this->Valorrecibo->find('all', $optionsValorecibo);
        $remunerativos=[];
        $noremunerativos=[];
        foreach ($valorRecibos as $valorrecibo) {
            $this->set($valorrecibo);
            if($valorrecibo['Cctxconcepto']['concepto_id']=='27'){
                //Total Remunerativos
                if(!isset($remunerativos[$valorrecibo['Valorrecibo']['periodo']]))
                    $remunerativos[$valorrecibo['Valorrecibo']['periodo']]=0;
                $remunerativos[$valorrecibo['Valorrecibo']['periodo']]+=$valorrecibo['Valorrecibo']['valor'];
               
            }
            if($valorrecibo['Cctxconcepto']['concepto_id']=='103'){
                //Total No Remunerativos
                //solo evaluar si los valores sond e periodos de solo 3 meses atras
                $datetime1 = date_create('01-'.$periodo);
                $datetime2 = date_create('01-'.$valorrecibo['Valorrecibo']['periodo']);
                $interval = $datetime2->diff($datetime1);
                if($interval->format('%m months')*1<=3){
                    if(!isset($noremunerativos[$valorrecibo['Valorrecibo']['periodo']]))
                        $noremunerativos[$valorrecibo['Valorrecibo']['periodo']]=0;
                    $noremunerativos[$valorrecibo['Valorrecibo']['periodo']]+=$valorrecibo['Valorrecibo']['valor'];
                }
            }
        }
        foreach ($remunerativos as $key => $value) {
            if($mayorRemunerativo*1<$value*1){                    
                $mayorRemunerativo=$value*1;
            }
        }
        foreach ($noremunerativos as $key => $value) {
            if($mayorNORemunerativo*1<$value*1){
                $mayorNORemunerativo=$value*1;
            } 
        }
        $this->set(compact('empleado','mayorRemunerativo','mayorNORemunerativo','tipoliquidacion','numeroliquidacion','tieneLiquidacion'));
        $this->autoRender=false;
        if($this->RequestHandler->isAjax()){
            $this->layout = 'ajax';
        }
		$this->render('papeldetrabajosueldos');
	}
    public function papeldetrabajolibrosueldo($empid=null,$periodo=null,$tipoliquidacion=null){
		$pemes = substr($periodo, 0, 2);
		$peanio = substr($periodo, 3);
		$esMayorQueBaja = array(
			//HASTA es mayor que el periodo
			'OR'=>array(
				'SUBSTRING(Actividadcliente.baja,4,7)*1 < '.$peanio.'*1',
				'AND'=>array(
					'SUBSTRING(Actividadcliente.baja,4,7)*1 <= '.$peanio.'*1',
					'SUBSTRING(Actividadcliente.baja,1,2) <= '.$pemes.'*1'
				),
			)
		);
		$options = [
            'contain'=>[
				'Cargo',
				'Domicilio'=>[
                    'Localidade'=>[
                        'Partido'
                    ]
                ],
                'Cliente'=>[
                    'Actividadcliente'=>[
                        'Actividade',
						'conditions'=>[
							'OR'=>[
								"Actividadcliente.baja IS NULL OR Actividadcliente.baja = ''",
								$esMayorQueBaja
							]
						]
                    ],
					'Impcli'=>[
						'conditions'=>[
							'Impcli.impuesto_id'=>10/*vamos a llevar el suss para sacar los datos*/
						]
					],
                ],
                'Valorrecibo'=>[
                    'Cctxconcepto'=>[
                        'Concepto',
                        'Conveniocolectivotrabajo'=>[

                        ]
                    ],
                    'conditions'=>[
                        'Valorrecibo.periodo'=>$periodo,
						'Valorrecibo.tipoliquidacion'=>$tipoliquidacion,
                    ]
                ],
            ],
            'conditions' => array('Empleado.id' => $empid)
        ];
        $empleado = $this->Empleado->find('first', $options);
        $this->set('empleado',$empleado);
        $this->set(compact('empid','periodo'));
        $this->autoRender=false;
       // $this->layout = 'ajax';
        $this->render('papeldetrabajolibrosueldo');
    }
    public function papeldetrabajorecibosueldo($empid=null,$periodo=null,$tipoliquidacion=null){
		$this->loadModel('Vencimiento');
		$this->loadModel('Impcli');
		$this->loadModel('Impuesto');

		$timePeriodo = strtotime("01-".$periodo ." -1 months");
		$periodoPrevio = date("m-Y",$timePeriodo);
		$fchvto = date('d-m-Y',$timePeriodo);
		$fchvtoOrigen="diaDeHoy";
		$pemesActual = substr($periodo, 0, 2);
		$peanioActual = substr($periodo, 3);

		$pemes = substr($periodoPrevio, 0, 2);
		$peanio = substr($periodoPrevio, 3);

        $this->set(compact('pemes','peanio'));
        $options = array(
            'contain'=>array(
				'Cargo',
				'Domicilio'=>array(
                    'Localidade'=>array(
                        'Partido'
                    )
                ),
                'Cliente'=>[
					'Impcli'=>[
						'Impuesto'=>[

						],
						'conditions'=>[
							'Impcli.impuesto_id'=>'10'/*SUSS*/
						]
					],
                    'Actividadcliente'=>[
                        'Actividade'
                    ]
                ],
                'Valorrecibo'=>array(
                    'Cctxconcepto'=>array(
                        'Concepto',
                        'Conveniocolectivotrabajo'=>array(

                        )
                    ),
                    'conditions'=>array(
                        'Valorrecibo.periodo'=>$periodo,
						'Valorrecibo.tipoliquidacion'=>$tipoliquidacion,

					)
                ),
            ),
            'conditions' => array('Empleado.id' => $empid)
        );
        $empleado = $this->Empleado->find('first', $options);
        $this->set('empleado',$empleado);
        $this->set(compact('empid','periodo'));
		//Aca vamos a hacer las busquedas pertinentes para mostrar la ultima fecha de vencimiento del SUSS
		// para este contribuyente y para que seleccione el banco en el que lo hizo

		$cliusuarioafip = $empleado["Cliente"]["cuitcontribullente"];
		$id = 0;
		if(count($empleado['Cliente']['Impcli'])>0){
			$id = $empleado['Cliente']['Impcli'][0]["Impuesto"]["id"];
		}
		$optionsVencimientoImpuesto = array(
			'conditions'=>array(
                $peanioActual.'*1 = Vencimiento.ano*1',
				'Vencimiento.desde <= SUBSTRING("'.$cliusuarioafip.'",-1)',
				'Vencimiento.hasta >= SUBSTRING("'.$cliusuarioafip.'",-1)',
				'Vencimiento.impuesto_id'=>$id,
			),
		);
		$vencimiento = $this->Vencimiento->find('first',$optionsVencimientoImpuesto);
		if(isset($vencimiento['Vencimiento']['p'.$pemesActual])&&$vencimiento['Vencimiento']['p'.$pemesActual]!=0){
			$strfchvto = strtotime($vencimiento['Vencimiento']['ano'].'-'.$pemesActual.'-'.$vencimiento['Vencimiento']['p'.$pemesActual]);
			$fchvto = date('d-m-Y',$strfchvto);
			$fchvtoOrigen="VencimientoRecomendado";
		}
		$this->set(compact('fchvto','fchvtoOrigen'));

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
		$conditionsImpCliHabilitadosImpuestos = array(
			//El periodo esta dentro de un desde hasta
			'AND'=> array(
				'Periodosactivo.impcli_id = Impcli.id',
				$esMayorQueDesde,
				'OR'=> array(
					$esMenorQueHasta,
					$periodoNull
				)
			)

		);
		$clienteImpuestosOptions = array(
			'conditions' => array(
				'Impcli.cliente_id'=> $empleado['Cliente']['id']
			),
			'fields'=>array('Impcli.id','Impuesto.nombre'),
			'joins'=>array(
				array('table'=>'impuestos',
					'alias' => 'Impuesto',
					'type'=>'inner',
					'conditions'=> array(
						'Impcli.impuesto_id = Impuesto.id',
						'AND'=>array(
							'Impuesto.organismo = "banco"'
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
		$this->set('impclis', $impclis);

		$bancosOptions = array(
			'conditions' => array(
				'Impuesto.organismo'=> 'banco'
			),
		);
		$impuestos=$this->Impuesto->find('list',$bancosOptions);
		$this->set('impuestos', $impuestos);


        $this->autoRender=false;
        $this->layout = 'ajax';
        $this->render('papeldetrabajorecibosueldo');
    }
    public function formulario102($empid=null,$periodo=null,$tipoliquidacion=null){
		$this->loadModel('Vencimiento');
		$this->loadModel('Impcli');
		$this->loadModel('Impuesto');
		$this->loadModel('Empleado');
		$this->loadModel('Cliente');

		$timePeriodo = strtotime("01-".$periodo ." -1 months");
		$periodoPrevio = date("m-Y",$timePeriodo);

		$pemes = substr($periodoPrevio, 0, 2);
		$peanio = substr($periodoPrevio, 3);

        $this->set(compact('pemes','peanio'));
        $options = array(
            'contain'=>array(
				'Cargo',
				'Domicilio'=>array(
                    'Localidade'=>array(
                        'Partido'
                    )
                ),
                'Valorrecibo'=>array(
                    'Cctxconcepto'=>array(
                        'Concepto',
                        'Conveniocolectivotrabajo'=>array(
                        )
                    ),
                    'conditions'=>array(
                        'Valorrecibo.periodo'=>$periodo,
						'Valorrecibo.tipoliquidacion'=>$tipoliquidacion,
					)
                ),
            ),
            'conditions' => array('Empleado.id' => $empid)
        );
        $empleado = $this->Empleado->find('first', $options);
        $this->set('empleado',$empleado);
        $this->set('cliid',$empleado['Empleado']['cliente_id']);
        $cliente=$this->Cliente->find('first', array(
                'contain'=>array(
                    'Domicilio'
                ),
                'conditions' => array(
                    'id' => $empleado['Empleado']['cliente_id'],
                ),
            )
        );
        $this->set(compact('empid','periodo','cliente'));
        $this->autoRender=false;
        $this->layout = 'ajax';
        $this->render('formulario102');
    }
    public function add() {
            $respuesta = array('respuesta'=>'');
            if ($this->request->is('post')) {
                    $this->Empleado->create();
        if(isset($this->request->data['Empleado']['fechaingresoedit'])){
            $this->request->data['Empleado']['fechaingreso']=$this->request->data['Empleado']['fechaingresoedit'];
        }
                    if(isset($this->request->data['Empleado']['fechaaltaedit'])){
            $this->request->data['Empleado']['fechaalta']=$this->request->data['Empleado']['fechaaltaedit'];
        }
        if(isset($this->request->data['Empleado']['fechaegresoedit'])){
            $this->request->data['Empleado']['fechaegreso']=$this->request->data['Empleado']['fechaegresoedit'];
        }
                    $this->request->data('Empleado.fechaingreso',date('Y-m-d',strtotime($this->request->data['Empleado']['fechaingreso'])));
                    $this->request->data('Empleado.fechaalta',date('Y-m-d',strtotime($this->request->data['Empleado']['fechaalta'])));
                    if($this->request->data['Empleado']['fechaegreso']) {
                            $this->request->data('Empleado.fechaegreso', date('Y-m-d', strtotime($this->request->data['Empleado']['fechaegreso'])));
                    }
                    if ($this->Empleado->save($this->request->data)) {
                            $this->request->data('Empleado.fechaingreso',date('d-m-Y',strtotime($this->request->data['Empleado']['fechaingreso'])));
                            $respuesta['empleado'] = $this->request->data;
                            if(!isset($respuesta['empleado']['Empleado']['id'])||$respuesta['empleado']['Empleado']['id']==''){
                                    $respuesta['empleado']['Empleado']['id'] = $this->Empleado->getLastInsertID();
                            }
                            $respuesta['respuesta'] = 'Se ha guardado el empleado con exito';
                    } else {
                            $respuesta['error'] = '1';
                            $respuesta['respuesta'] = 'NO se ha guardado el empleado con exito. Por favor intente de nuevo mas tarde.';
                    }
                    $this->set('data',$respuesta);
                    $this->autoRender=false;
                    $this->layout = 'ajax';
                    $this->render('serializejson');
                    return;
            }
            $this->set('respuesta',$respuesta);
            $this->autoRender=false;
            $this->layout = 'ajax';
            $this->render('add');
            return;
    }
    public function edit($id = null) {
            $this->loadModel('Domicilio');
            $this->loadModel('Conveniocolectivotrabajo');
            $this->loadModel('Cargo');
            $this->loadModel('Impuesto');
            $this->loadModel('Localidade');
            $this->loadModel('Obrassociale');
            if (!$this->Empleado->exists($id)) {
                    throw new NotFoundException(__('Invalid empleado'));
            }
            $options = array('conditions' => array('Empleado.' . $this->Empleado->primaryKey => $id));
            $this->request->data = $this->Empleado->find('first', $options);

            $this->set(compact('cliid'));

            $optionsDomic = array(
                    'conditions' => array('Domicilio.cliente_id' => $this->request->data['Empleado']['cliente_id'])
            );
            $domicilios = $this->Domicilio->find('list',$optionsDomic);
            $this->set('domicilios', $domicilios);

            $conveniocolectivotrabajos = $this->Conveniocolectivotrabajo->find('list');
            $this->set('conveniocolectivotrabajos', $conveniocolectivotrabajos);

            $this->set('cargos',$this->Cargo->find('list',[
                            'contain'=>[
                                    'Conveniocolectivotrabajo'
                            ],
                            'fields'=>[
                                    'Cargo.id','Cargo.nombre','Conveniocolectivotrabajo.nombre'
                            ]
                    ]
            )
            );

            //aca vamos a setiar las listas que se necesita para cargar empleados
            $this->set('codigorevista',$this->Empleado->codigorevista);
            $this->set('codigocondicion',$this->Empleado->codigocondicion);
            $this->set('codigoactividad',$this->Empleado->codigoactividad);
            $this->set('codigomodalidadcontratacion',$this->Empleado->codigomodalidadcontratacion);
            $this->set('codigosiniestrado',$this->Empleado->codigosiniestrado);
            $this->set('tipoempresa',$this->Empleado->tipoempresa);
            $this->set('codigozona',$this->Empleado->codigozona);
            $this->set('cargos',$this->Cargo->find('list',[
                            'contain'=>[
                                    'Conveniocolectivotrabajo'
                            ],
                            'fields'=>[
                                    'Cargo.id','Cargo.nombre','Conveniocolectivotrabajo.nombre'
                            ]
                    ]
            )
            );
            $bancosOptions = array(
                    'conditions' => array(
                            'Impuesto.organismo'=> 'banco'
                    ),
            );
            $bancos=$this->Impuesto->find('list',$bancosOptions);
            $this->set('bancos', $bancos);

            $optionsLoc = array(
                    'contain'=>array('Partido'),
                    'conditions' => array( ),
                    'fields'=> array('Localidade.id','Localidade.nombre','Partido.nombre'),
                    'order'=>array('Partido.nombre','Localidade.nombre')
            );

            $localidades = $this->Localidade->find('list',$optionsLoc);
            $this->set('localidades', $localidades);
            $optionsOS = [];
            $obrassociales = $this->Obrassociale->find('list',$optionsOS);
            $this->set('obrassociales', $obrassociales);
            $this->autoRender=false;
            if($this->RequestHandler->isAjax()){
                $this->layout = 'ajax';
            }
            $this->autoRender=false;
            
            $this->render('edit');
    }
    public function delete($id = null) {
		$id = substr($id,0, -5);
		$this->Empleado->id = $id;
		if (!$this->Empleado->exists()) {
			throw new NotFoundException(__('Invalid Empleado'));
		}
		$this->request->onlyAllow('post', 'delete');
		$data=array();

		$options = array(
			'contain'=>array('Valorrecibo'),
			'conditions' => array('Empleado.' . $this->Empleado->primaryKey => $id));
		$miEmpleado = $this->Empleado->find('first', $options);

		if(count($miEmpleado['Valorrecibo'])>0){
			$data['error'] = "El Empleado tiene liquidaciones ya cargadas por favor eliminelas antes de borrar este empleado";
			$data['respuesta'] = "El Empleado tiene liquidaciones ya cargadas por favor eliminelas antes de borrar este empleado";
		}else {
			if ($this->Empleado->delete()) {
				$data['respuesta'] = "El Empleado ha a sido eliminado.";
//				if ($this->Empleado->Valorrecibo->deleteAll(array('Valorrecibo.empleado_id' => $id), false)) {
//					$data['respuesta'] .= " Se han eliminado los recibos guardados para este empleado";
//				}
			} else {
				$data['respuesta'] = "El Empleado NO ha sido eliminado.Por favor intente de nuevo mas tarde.";
			}
		}
		$this->set(compact('data'));
		$this->layout = 'ajax';
		$this->render('serializejson');
	}
}
