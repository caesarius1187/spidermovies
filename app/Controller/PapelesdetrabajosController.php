<?php
App::uses('AppController', 'Controller');
/**
 * Archivos Controller
 *
 * @property Archivo $Archivo
 * @property PaginatorComponent $Paginator
 */
class PapelesdetrabajosController extends AppController {

	var $uses = false;
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
	public function iva($ClienteId = null,$periodo=null) {
		ini_set('memory_limit', '2560M');
		$this->loadModel('Cliente');
		$this->loadModel('Venta');
		$this->loadModel('Actividadcliente');
        $this->loadModel('Conceptosrestante');
        $this->loadModel('Compra');
        $this->loadModel('Cuenta');
		$this->loadModel('Cuentascliente');
		$this->loadModel('Tipogasto');
		//$this->Archivo->recursive = 0;
		//$this->set('archivos', $this->Paginator->paginate());
		$añoPeriodo="SUBSTRING( '".$periodo."',4,7)";
		$mesPeriodo="SUBSTRING( '".$periodo."',1,2)";
		$esMenorQuePeriodo = array(
			'OR'=>array(
	    		'SUBSTRING(Eventosimpuesto.periodo,4,7)*1 < '.$añoPeriodo.'*1',
	    		'AND'=>array(
	    			'SUBSTRING(Eventosimpuesto.periodo,4,7)*1 = '.$añoPeriodo.'*1',
	    			'SUBSTRING(Eventosimpuesto.periodo,1,2) < '.$mesPeriodo.'*1'
	    			),												            		
	    		)
		);
		$timePeriodo = strtotime("01-".$periodo ." -1 months");
		$periodoPrev = date("m-Y",$timePeriodo);
		$cuentasIVA = $this->Cuenta->cuentasdeIVA;
		$this->set('cuentasIVA', $cuentasIVA);
		$pemes = substr($periodo, 0, 2);
		$peanio = substr($periodo, 3);
		$bajaesMayorQuePeriodo = array(
			'OR'=>array(
				'SUBSTRING(Actividadcliente.baja,4,7)*1 > '.$peanio.'*1',
				'AND'=>array(
					'SUBSTRING(Actividadcliente.baja,4,7)*1 >= '.$peanio.'*1',
					'SUBSTRING(Actividadcliente.baja,1,2) >= '.$pemes.'*1'
				),
			)
		);
		$options = 
			[
			'conditions' => ['Cliente.' . $this->Cliente->primaryKey => $ClienteId],
			'contain' => [
				'Impcli'=>[
					'Impuesto'=>[
						'Asientoestandare'=>[
							'conditions'=>[
								'tipoasiento'=>['impuestos','impuestos2']
							],
							'Cuenta'
						],
					],
					'Eventosimpuesto'=>[
						'conditions'=>[
							"Eventosimpuesto.periodo"=>[$periodoPrev,$periodo],
							//monto a favor del periodo anterior
							],
						],
					'Conceptosrestante'=>[
						'conditions'=>[
							'Conceptosrestante.periodo' => $periodo,
						],
					],
                    'Asiento'=>[
                        'Movimiento'=>[
							'Cuentascliente'
						],
                        'conditions'=>['periodo'=>$periodo]
                    ],
					'conditions'=>[
						'Impcli.impuesto_id'=>19//IVA
						]
					],
				'Actividadcliente' => [
					'Actividade',
					'conditions'=>[
						//traer solo las actividades que tengan periodo baja null "" o que sean menor que el periodo
						'OR'=>[
							$bajaesMayorQuePeriodo,
							'Actividadcliente.baja = ""',
							'Actividadcliente.baja = "0000-00-00"',
							'Actividadcliente.baja is null' ,
						]
					],
				],
				'Cuentascliente'=>[
					'Cuenta',
					'conditions'=>[
						'Cuentascliente.cuenta_id' => $cuentasIVA
					]

				]
			],
		];

		$Cliente = $this->Cliente->find('first', $options);
        $this->set('cliente', $Cliente);
		$conceptosOptions=[
            'Usosaldo'=>[
                'Eventosimpuesto'=>[

                ]
            ],
			'conditions'=>[
                'Conceptosrestante.impcli_id'=>$Cliente['Impcli'][0]['id'],
                'Conceptosrestante.periodo'=>$periodoPrev,
                'Conceptosrestante.conceptostipo_id'=>1
            ]
		];
		$saldosLibreDisponibilidad = $this->Conceptosrestante->find('all',$conceptosOptions);
        $this->set('saldosLibreDisponibilidad', $saldosLibreDisponibilidad);
        $this->set('periodo', $periodo);
        $this->set('periodoPrev', $periodoPrev);

		$opcionesActividad = array(
								   'conditions'=>array(
									   'Actividadcliente.cliente_id' => $ClienteId,
                                       //traer solo las actividades que tengan periodo baja null "" o que sean menor que el periodo
                                       'OR'=>[
                                           $bajaesMayorQuePeriodo,
                                           'Actividadcliente.baja = ""',
                                           'Actividadcliente.baja = "0000-00-00"',
                                           'Actividadcliente.baja is null' ,
                                       ]
								   ),
								   'contain'=> array(
								   	  'Actividade',
								 	)
							 );
		$actividades = $this->Actividadcliente->find('all', $opcionesActividad);

		/*
		SELECT * FROM actividadclientes 
		INNER JOIN actividades ON actividades.id = actividadclientes.actividade_id
		INNER JOIN ventas ON ventas.actividadcliente_id = actividadclientes.id 
		AND ventas.cliente_id = actividadclientes.cliente_id
		WHERE actividadclientes.cliente_id = 4
		*/

		$opcionesVenta = array(
								'conditions'=>array(
									'Venta.cliente_id' => $ClienteId,
									'Venta.periodo' => $periodo,
									),
								'contain' => array(
									'Comprobante' => [],
									'Actividadcliente' => array(
                                        'conditions'=>array(
                                            //traer solo las actividades que tengan periodo baja null "" o que sean menor que el periodo
                                            'OR'=>[
                                                $bajaesMayorQuePeriodo,
                                                'Actividadcliente.baja = ""',
                                                'Actividadcliente.baja = "0000-00-00"',
                                                'Actividadcliente.baja is null' ,
                                            ]
                                        ),
                                        'Actividade',
                                        )
								  )
							  );
		$ventas = $this->Venta->find('all', $opcionesVenta);

		$opcionesCompra = array(
			'fields'=>[
				'Compra.actividadcliente_id','Compra.tipocredito','Compra.imputacion','Compra.condicioniva','Compra.tipoiva',
                'Compra.alicuota',
				'SUM(neto)as neto',
				'SUM(exentos)as exentos',
				'SUM(nogravados)as nogravados',
				'SUM(iva)as iva',
				'SUM(ivapercep)as ivapercep' ],
			'conditions'=>array(
				'Compra.cliente_id' => $ClienteId,
				'Compra.periodo' => $periodo,
				),
			'contain' => array(
                'Actividadcliente' => [
                        'fields'=>['actividade_id'],
						'conditions'=>[
							//traer solo las actividades que tengan periodo baja null "" o que sean menor que el periodo
							'OR'=>[
								$bajaesMayorQuePeriodo,
								'Actividadcliente.baja = ""',
								'Actividadcliente.baja = "0000-00-00"',
								'Actividadcliente.baja is null' ,
							]
						],
                                        ]
                //'actividades' => array(
                //						'conditions' => array('Actividade.id' => 'Actividadcliente.actividade_id')
                //					   )
              ),
			'group'=>[
				'Compra.actividadcliente_id','Compra.tipocredito','Compra.imputacion','Compra.tipoiva','Compra.alicuota'
				,'Compra.condicioniva'
			]
		  );
		$compras = $this->Compra->find('all', $opcionesCompra);

		//aca vamos a buscar los movimientos bancarios que pertenezcan a la cuenta
		//286   110403401   IVA - Credito Fiscal General
		$optionsCuentascliente=[
			'contain'=>[
				'Movimientosbancario'=>[
					'conditions'=>[
						'Movimientosbancario.periodo'=>$periodo
					]
				]
			],
			'conditions'=>[
				'Cuentascliente.cuenta_id = 286',
				'Cuentascliente.cliente_id'=>$ClienteId,
			]
		];
		$cuentascliente=$this->Cuentascliente->find('all', $optionsCuentascliente);


		$this->set('cuentascliente', $cuentascliente);
		$this->set('actividades', $actividades);
		$this->set('ventas', $ventas);
		$this->set('compras', $compras);
		
		$ingresosBienDeUso = $this->Tipogasto->ingresosBienDeUso;
		$this->set('ingresosBienDeUso',$ingresosBienDeUso);
	}
	public function autonomo($impcliid=null, $periodo=null){
		$this->loadModel('Autonomocategoria');
		$this->loadModel('Impcli');
		$optionCategorias = [
			'contain'=>[
				'Autonomoimporte'=>[
					'conditions'=>[
						"Autonomoimporte.desde < '".date('Y-m-d',strtotime('02-'.$periodo))."'"
					],
                    'order'=>[
                        "Autonomoimporte.desde desc"
                    ]
				]
			],
			'order'=>[
				'rubro',
				'tipo',
				'tabla',
				'categoria',
			]
		];
		$autonomocategorias = $this->Autonomocategoria->find('all',$optionCategorias);
		$options = [
			'conditions' => ['Impcli.id' => $impcliid],
			'contain' => [
				'Cliente'=>[
					'Cuentascliente'
				],
				'Asiento'=>[
					'Movimiento'=>[
						'Cuentascliente'
					],
					'conditions'=>[
						'Asiento.periodo'=>$periodo,
						'Asiento.tipoasiento'=>'impuestos'
					]
				],
				'Impuesto'=>[
					'Asientoestandare'=>[
						'conditions'=>[
							'tipoasiento'=>['impuestos']
						],
						'Cuenta'
					],
				],
			],

		];
		$impcli = $this->Impcli->find('first', $options);

		$this->set(compact('autonomocategorias','impcli','periodo','impcliid'));
	}
	public function estadoderesultado($clienteid = null, $periodo = null){
		$this->loadModel('Cliente');
		$this->loadModel('Movimiento');
		$this->loadModel('Cuentascliente');

		$pemes = substr($periodo, 0, 2);
		$peanio = substr($periodo, 3);

		$optionmiCliente = [
			'contain' => [
			],
			'conditions' => ['Cliente.id'=>$clienteid]
		];
		$micliente = $this->Cliente->find('first',$optionmiCliente);
		$fechadeconsulta = date('Y-m-t',strtotime("01-".$pemes."-".$peanio));

		if(!isset($micliente['Cliente']['fchcorteejerciciofiscal'])||is_null($micliente['Cliente']['fchcorteejerciciofiscal'])||$micliente['Cliente']['fchcorteejerciciofiscal']==""){
                    $this->Session->setFlash(__('No se ha configurado fecha decorte de ejercicio fiscal para este
                     ccontribuyente .'));
                    $fechadecorteAñoActual = date('Y-m-d',strtotime("01-01-".$peanio));
		}else{
                    $fechadecorteAñoActual = date('Y-m-d',strtotime($micliente['Cliente']['fchcorteejerciciofiscal']."-".$peanio));
		}
		$fechaInicioConsulta = "";
		$fechaInicioPeriodoAnterior = "";
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
		//la fecha fin consulta es esta por que solo vamos a ver hasta el ultimo dia del periodo que estamos
		// consultando
                $fechaFinConsulta =  date('Y-m-t',strtotime($fechadeconsulta));
   
                
		//$fechaFinConsulta  = date('Y',strtotime("01-".$pemes."-".$peanio));
		//$fechadeconsulta = date('Y',strtotime("01-".$pemes."-".$peanio." -1 Year"));                                                
		//$fechaInicioConsulta = $fechadeconsulta;
		//$fechaFinConsulta = $fechaFinConsulta;
		$this->set('fechaInicioConsulta',$fechaInicioConsulta);
		$this->set('fechaFinConsulta',$fechaFinConsulta);

		$optionCliente = [
			'contain' => [
				'Cuentascliente'=>[
                                        'Cuentaclientevalororigen',
                                        'Cuentaclienteactualizacion',
                                        'Cuentaclienteterreno',
                                        'Cuentaclienteedificacion',
                                        'Cuentaclientemejora',
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
								AND    Asiento.fecha  <= '".$fechaFinConsulta."'
							)"
						]
					],
					'conditions'=>[

					]
				],
			],
			'conditions' => ['Cliente.id'=>$clienteid]
		];
		$cliente = $this->Cliente->find('first',$optionCliente);
		$this->set('cliente',$cliente);
		$this->set('periodo',$periodo);
	}
}
/*
 * buscar el asiento de apertura del año anterior
 * sumarle las compras
 * restarle las devoluciones
 * eso es la existencia final
 */

