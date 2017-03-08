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
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Empleado->recursive = 0;
		$this->set('empleados', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
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

        $optionsLiquidaciones = array(
            'contain'=>array(
                'Conveniocolectivotrabajo'=>array(
                    'Cctxconcepto'=>array(
                        'Valorrecibo'=>array(
                            'conditions'=>array(
                                'Valorrecibo.empleado_id'=>$empleadoamostrar,
                            ),
                            'fields'=>array('Distinct(Valorrecibo.tipoliquidacion)'),
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
                        'fields'=>array('id'),
                        'limit' => 1
                    ),
                    'fields'=>array('id'),
                ),
            ),
            'fields'=>array('id','liquidaprimeraquincena','liquidasegundaquincena','liquidamensual','liquidapresupuestoprimera','liquidapresupuestosegunda','liquidapresupuestomensual'),
            'conditions' => array('Empleado.' . $this->Empleado->primaryKey => $empleadoamostrar)
        );
        $tieneLiquidacion = $this->Empleado->find('first', $optionsLiquidaciones);

		if(!isset($tipoliquidacion)||$tipoliquidacion==null||$tipoliquidacion==""||$tipoliquidacion=="undefined"){
            if($tieneLiquidacion['Empleado']['liquidaprimeraquincena']==1){
                $tipoliquidacion=1;
            }elseif ($tieneLiquidacion['Empleado']['liquidasegundaquincena']) {
                $tipoliquidacion = 2;
            }elseif ($tieneLiquidacion['Empleado']['liquidamensual']) {
                $tipoliquidacion = 3;
            }elseif ($tieneLiquidacion['Empleado']['liquidapresupuestoprimera']) {
                $tipoliquidacion = 4;
            }elseif ($tieneLiquidacion['Empleado']['liquidapresupuestosegunda']) {
                $tipoliquidacion = 5;
            }elseif ($tieneLiquidacion['Empleado']['liquidapresupuestomensual']) {
                $tipoliquidacion = 6;
            }
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
								'Valorrecibo.tipoliquidacion'=>$tipoliquidacion,
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
			'conditions' => array('Empleado.' . $this->Empleado->primaryKey => $empleadoamostrar)
		);
		$empleado = $this->Empleado->find('first', $optionsempleados);
		/*
         * Primero vamos a ordenar los cctxconcepto en base a la seccion a la que pertenece
         * */
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
		for ($i=0;$i<count($empleado['Conveniocolectivotrabajo']['Cctxconcepto'])-1;$i++){
			for ($j=$i;$j<count($empleado['Conveniocolectivotrabajo']['Cctxconcepto']);$j++) {
				$ordenburbuja = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['orden'];
                $ordenaux = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]['orden'];
                $ordenaux = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]['orden'];

                $seccionburbuja = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]['Concepto']['seccion'];
                $seccionaux = $empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]['Concepto']['seccion'];

				if($ordenburbuja>$ordenaux&&$seccionburbuja==$seccionaux){
					$myaux=$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i];
					$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$i]=$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j];
					$empleado['Conveniocolectivotrabajo']['Cctxconcepto'][$j]=$myaux;
				}
			}
		}

		$this->set(compact('empleado','tipoliquidacion','tieneLiquidacion'));
		$this->autoRender=false;
		$this->layout = 'ajax';
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
                'Domicilio'=>[
                    'Localidade'=>[
                        'Partido'
                    ]
                ],
                'Cliente'=>[
                    'Actividadcliente'=>[
                        'Actividade',
						'conditions'=>$esMayorQueBaja
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
        $this->layout = 'ajax';
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
		$pemes = substr($periodoPrevio, 0, 2);
		$peanio = substr($periodoPrevio, 3);
		
        $options = array(
            'contain'=>array(
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

		switch ($pemes) {
			case '12':

			default:
				$optionsVencimientoImpuesto = array(
					'conditions'=>array(
						$peanio.'*1 = Vencimiento.ano*1',
						'Vencimiento.desde <= SUBSTRING("'.$cliusuarioafip.'",-1)',
						'Vencimiento.hasta >= SUBSTRING("'.$cliusuarioafip.'",-1)',
						'Vencimiento.impuesto_id'=>$empleado['Cliente']['Impcli'][0]["Impuesto"]["id"],
					),
				);
				$vencimiento = $this->Vencimiento->find('first',$optionsVencimientoImpuesto);
				if(isset($vencimiento['Vencimiento']['p'.$pemes])&&$vencimiento['Vencimiento']['p'.$pemes]!=0){
					$strfchvto = strtotime($vencimiento['Vencimiento']['ano'].'-'.$pemes.'-'.$vencimiento['Vencimiento']['p'.$pemes]);
					$fchvto = date('d-m-Y',$strfchvto);
					$fchvtoOrigen="VencimientoRecomendado";
				}
				break;
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

		$this->autoRender=false;
		$this->layout = 'ajax';
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
	}}
