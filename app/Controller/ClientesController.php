<?php
App::uses('AppController', 'Controller');
/**
 * Clientes Controller
 *
 * @property Cliente $Cliente
 * @property PaginatorComponent $Paginator
 */
class ClientesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','RequestHandler');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$conditionsCli = array(
							 'Grupocliente',
							 );
		$clientes = $this->Cliente->find('list',array(
										'contain' =>$conditionsCli,
										'conditions' => array(
								 			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),								 		
								 			),	
										)
									);
		$this->redirect(array('action' => 'view'));	
	}
	public function avance() {
		$this->loadModel('Impcli');
		$this->loadModel('Tareasxclientesxestudio');
		$this->loadModel('Tareascliente');
		$this->loadModel('Tareasimpuesto');
		$this->loadModel('Grupocliente');
		$this->loadModel('Periodosactivo');
		$this->loadModel('Impuesto');
		$pemes="";
		$peanio="";
		$selectby="";
		
		//Como Buscar los clientes
		$conditionsClientesAvance = array();
		$condicionDeSolicitarParaCliente = array();	
		if (isset($this->request->data['clientes']['periodomes'])) {
			$pemes=$this->request->data['clientes']['periodomes'];
			$peanio=$this->request->data['clientes']['periodoanio'];
			$filtrodesolicitar=$this->request->data['clientes']['filtrodesolicitar'];
			$selectby=$this->request->data['clientes']['selectby'];			
			$this->set('periodomes', $pemes);
			$this->set('periodoanio', $peanio);
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
			$periodoNull = array(
				'OR'=>array(
            		array('Periodosactivo.hasta'=>null),
            		array('Periodosactivo.hasta'=>""),												            		
            		)
			);
			//C: Tiene Periodo Hasta 0 NULL
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
			$aplicafiltro=false;
			if($selectby=='clientes'){
				$conditionsClientesAvance =  array(
									                'Cliente.id' => $this->request->data['clientes']['lclis'],  
									                'Cliente.estado' => 'habilitado',  
									                'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),				
									                'Grupocliente.estado' => 'habilitado',  
									            );
			}else if($selectby=='grupos'){
				$conditionsClientesAvance = array(
									                'Cliente.grupocliente_id' => $this->request->data['clientes']['gclis'],  
									                'Cliente.estado' => 'habilitado',  
									                'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),				
									                'Grupocliente.estado' => 'habilitado',  
									            );
			}else if($selectby=='impuestos'){
				$conditionsClientesAvance = array(									                
									                'Cliente.estado' => 'habilitado',  
									                'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),				
									                'Grupocliente.estado' => 'habilitado',  
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
					'contain'=>array(
						
						),
					'conditions' => array(
							'Impcli.impuesto_id'=> $this->request->data['clientes']['filtrodeimpuestos']
						), 
					'fields'=>array('Impcli.cliente_id'),
					'joins'=>array(
						array('table'=>'periodosactivos', 
			                 'alias' => 'Periodosactivo',
			                 'type'=>'inner',
			                 'conditions'=> array(
			                 	$conditionsImpCliHabilitadosImpuestos
			           			)
		                 	),
						)
					);
				$condicionDeSolicitarParaCliente=$this->Impcli->find('list',$clienteImpuestosOptions);
				$aplicafiltro=true;
			}else if($selectby=='solicitar'){
				//Aca vamos a compretar el array $condicionDeSolicitarParaCliente que especifica los clientes que cumplen con las condiciones 
				//necesarias para superar el filtro seleccionado
				$conditionsClientesAvance = array(									                
									                'Cliente.estado' => 'habilitado',  
									                'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),				
									                'Grupocliente.estado' => 'habilitado',  
									            );
				switch ($filtrodesolicitar) {
					case 'banco':
						$conditionsImpCliHabilitadosBanco = array(
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
						$clienteBancoOptions = array(
							'contain'=>array(
								'Impuesto'=>array(
									)
								),
							'conditions' => array(
									'Impuesto.organismo'=>'banco'
								), 
							'fields'=>array('Impcli.cliente_id'),
							'joins'=>array(
								array('table'=>'periodosactivos', 
					                 'alias' => 'Periodosactivo',
					                 'type'=>'inner',
					                 'conditions'=> array(
					                 	$conditionsImpCliHabilitadosBanco
					           			)
				                 	),
								)
							);
						$condicionDeSolicitarParaCliente=$this->Impcli->find('list',$clienteBancoOptions);
						break;
					case 'tarjetadecredito':
						$clienteFacturaEnLinea = array(											
							'fields'=>array('Impcli.cliente_id'),
							'joins'=>array(
								array('table'=>'puntosdeventas', 
					                 'alias' => 'Puntosdeventa',
					                 'type'=>'inner',
					                 'conditions'=> array(
					                 		'Puntosdeventa.cliente_id = Impcli.cliente_id',
					                 		'Puntosdeventa.sistemafacturacion = "Factura en Linea"'
					           			)
				                 	),
								)
							);
						$clientesConFacturaEnLinea=$this->Impcli->find('list',$clienteFacturaEnLinea);
						$this->set('clientesConFacturaEnLinea',array_unique(array_values($clientesConFacturaEnLinea)));
						$clienteTarjetaCreditoOptions=array(
							'conditions'=>array(
									"NOT" => array(
									        "Cliente.id" => array_unique(array_values($clientesConFacturaEnLinea))
								    )
								)
							);
						$condicionDeSolicitarParaCliente=array_keys($this->Cliente->find('list',$clienteTarjetaCreditoOptions));
						break;
					case 'descargawebafip':
						$clienteDescargaWebAfip = array(											
							'fields'=>array('Impcli.cliente_id'),
							'joins'=>array(
								array('table'=>'puntosdeventas', 
					                 'alias' => 'Puntosdeventa',
					                 'type'=>'inner',
					                 'conditions'=> array(
					                 		'Puntosdeventa.cliente_id = Impcli.cliente_id',
					                 		'Puntosdeventa.sistemafacturacion = "Factura en Linea"'
					           			)
				                 	),
								)
							);
						$condicionDeSolicitarParaCliente=array_keys($this->Impcli->find('list',$clienteDescargaWebAfip));
						break;
					case 'libroivaventas':
						$clienteDescargaWebAfip = array(											
							'fields'=>array('Impcli.cliente_id'),
							'joins'=>array(
								array('table'=>'puntosdeventas', 
					                 'alias' => 'Puntosdeventa',
					                 'type'=>'inner',
					                 'conditions'=> array(
					                 		'Puntosdeventa.cliente_id = Impcli.cliente_id',
					                 		'OR' => array(
					                 				'Puntosdeventa.sistemafacturacion = "Controlador Fiscal"',
					                 				'Puntosdeventa.sistemafacturacion = "RECE"',
				                 				 )
					           			)
				                 	),
								)
							);
						$condicionDeSolicitarParaCliente=array_values($this->Impcli->find('list',$clienteDescargaWebAfip));
						break;
					case 'fcluz':
						$conditionsImpCliHabilitadosFacturaLuz = array(
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
						$clienteFacturaLuzOptions = array(
							'contain'=>array(
								
								),
							'conditions' => array(
									'Impcli.impuesto_id'=> 4/*Monotributo*/
								), 
							'fields'=>array('Impcli.cliente_id'),
							'joins'=>array(
								array('table'=>'periodosactivos', 
					                 'alias' => 'Periodosactivo',
					                 'type'=>'inner',
					                 'conditions'=> array(
					                 	$conditionsImpCliHabilitadosFacturaLuz
					           			)
				                 	),
								)
							);
						$condicionDeSolicitarParaCliente=$this->Impcli->find('list',$clienteFacturaLuzOptions);
						break;
					case 'sueldos':
					case 'librounico':
						$conditionsImpCliHabilitadosLibroUnicoSUSS = array(
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
						$clienteLibroUnicoSUSSOptions = array(
							'contain'=>array(
								
								),
							'conditions' => array(
									'Impcli.impuesto_id'=> 10/*SUSS*/
								), 
							'fields'=>array('Impcli.cliente_id'),
							'joins'=>array(
								array('table'=>'periodosactivos', 
					                 'alias' => 'Periodosactivo',
					                 'type'=>'inner',
					                 'conditions'=> array(
					                 	$conditionsImpCliHabilitadosLibroUnicoSUSS
					           			)
				                 	),
								)
							);
						$condicionDeSolicitarParaCliente=$this->Impcli->find('list',$clienteLibroUnicoSUSSOptions);
						break;
					default:
						# code...
					break;
				}
				$aplicafiltro=true;
			}else{
				$conditionsClientesAvance = array(
									                'Cliente.estado' => 'habilitado',  
									                'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),				
									                'Grupocliente.estado' => 'habilitado',  
									            );
			}
			if($aplicafiltro){
				$conditionsClientesAvance['Cliente.id'] = $condicionDeSolicitarParaCliente;				
			}
			$this->set('condicionDeSolicitarParaCliente',$condicionDeSolicitarParaCliente);
			$this->set('conditionsClientesAvance',$conditionsClientesAvance);
			
			$this->Paginator->settings = array(
			   'contain'=>array(
			      'Grupocliente'=>array(
			      		'fields'=>array('id','nombre'),
			      	),
			      'Eventoscliente'=>array(
			      		'conditions' => array(
				                'Eventoscliente.periodo =' => $pemes.'-'.$peanio  
				            ),
			      		'fields'=>array('id','periodo','cliente_id','tarea1','tarea3','tarea14','banco','tarjetadecredito','fcventa','descargawebafip','fccompra','libroivaventas','fcluz','sueldos','librounico'),
			      	),
			      'Honorario'=>array(
			      		'conditions' => array(
				                'Honorario.periodo =' => $pemes.'-'.$peanio  
				            ),
			      		'fields'=>array('id','cliente_id','monto','fecha','periodo','estado','descripcion'),
			      	),
			      'Puntosdeventa'=>array(
			      		'fields'=>array('id','cliente_id','sistemafacturacion','nombre'),
			      	),
			      'Impcli'=>array(
			        'Impuesto'=>array(
			            'fields'=>array('id','nombre','abreviacion','orden','organismo'),
			            'conditions'=>array(
			             	),
			         ),
		        	'Eventosimpuesto'=>array(
			      		'fields'=>array('id','item','partido_id','localidade_id','periodo','montovto','impcli_id','tarea5','tarea13'),
			      		'conditions'=>array('Eventosimpuesto.periodo' => $pemes.'-'.$peanio  )
			      	), 
		        	'Periodosactivo'=>array(
		        	 		'conditions'=>$conditionsImpCliHabilitados,
		        	 	),
		      		'fields'=>array('Impcli.id','Impcli.cliente_id','Impcli.impuesto_id'),
					)									       
			    ),
			   'conditions' => $conditionsClientesAvance,
			   'limit' => 25,
  			   'fields'=>array('Cliente.id','Cliente.nombre','Cliente.grupocliente_id','Cliente.honorario'),
			   'order' => array(
				            ),
			);
			$clientes3 = $this->Paginator->paginate('Cliente');
			$posicion = 0;
			foreach ($clientes3 as $micliente) {
				/*Vamos a hacer los controles para saber que elementos mostrar dentro de la tarea "Solicitar"
					Comprobantes de Compras 
					Libros IVA Ventas
					Comprobantes de Ventas
					Descargar Ventas Web AFIP
					Resumenes Bancarios
					Tarjetas de Credito 
					Factura de Luz
					Novedades
					Libros Unicos.
				Hay que mejorar estas consultas por que no estan distinguiendo entre diferentes domicilios
				*/
				/*variables*/
					$cargaFacturaCompras=1;//Siempre se cargan facturas de compras
					$cargaFacturaVentas=1;//Siempre se cargan facturas de ventas
					$cargaLibroIVAVentas=0;
					//Se carga libro IVA ventas cuando un punto de venta que sea "Controlador Fiscal", "RECE para aplicativo y web services" 
					$cargaVentasWeb=0;
					//Se carga libro IVA ventas cuando un punto de venta que sea "Factura en Linea"
					$cargaTarjetasCredito=0;
					//si tiene puntos de venta en linea no se piden tarjetas de credito
					$cargaFacturaLuz=0;
					//se cargan facturas de luz si se tiene activado Monotributo
					$cargaNovedades=0;
					//se cargan novedades si se tiene activado SUSS
					$cargaLibroUnico=0;
					//se cargan Libro Unico si se tiene activado SUSS
					$cargaBanco=0;
					//se cargan bancos si hay algun impuesto del tipo banco activo
				if(count($micliente['Puntosdeventa'])!=0){
					$cargaTarjetasCredito=1;
				}
				foreach ($micliente['Puntosdeventa'] as $puntosdeventa) {
					switch ($puntosdeventa['sistemafacturacion']) {
						case 'Controlador Fiscal':
						case 'RECE':
							$cargaLibroIVAVentas=1;
							break;
						case 'Factura en Linea':
							$cargaVentasWeb=1;
							$cargaTarjetasCredito=0;
							break;
					}
				}
				$impclipos=0;
				foreach ($micliente['Impcli'] as $impcli) {
					if(count($impcli['Periodosactivo'])!=0){
						if($impcli['Impuesto']['organismo']=='banco'){
							$cargaBanco=1;
						}
						if($impcli['Impuesto']['id']==4/*Monotributo*/){
							$cargaFacturaLuz=1;
						}
						if($impcli['Impuesto']['id']==10/*SUSS*/){
							$cargaBanco=1;
							$cargaNovedades=1;
							$cargaLibroUnico=1;
						}
						$impclipos++;
					}else{
						array_splice($micliente['Impcli'], $impclipos, 1);
					}	
				}
			
				$micliente['Cliente']['cargaFacturaCompras']=$cargaFacturaCompras;
				$micliente['Cliente']['cargaFacturaVentas']=$cargaFacturaVentas;
				$micliente['Cliente']['cargaLibroIVAVentas']=$cargaLibroIVAVentas;
				$micliente['Cliente']['cargaVentasWeb']=$cargaVentasWeb;
				$micliente['Cliente']['cargaTarjetasCredito']=$cargaTarjetasCredito;
				$micliente['Cliente']['cargaFacturaLuz']=$cargaFacturaLuz;
				$micliente['Cliente']['cargaNovedades']=$cargaNovedades;
				$micliente['Cliente']['cargaLibroUnico']=$cargaLibroUnico;
				$micliente['Cliente']['cargaBanco']=$cargaBanco;
				
				$clientes3[$posicion]=$micliente;
				$posicion++;
			}
			//Aca vamos a ordenar los ImpClis del los clientes en funcion de los vencimientos de los Eventos Impuestos
			foreach ($clientes3 as $micliente) {
				foreach ($micliente['Impcli'] as $impcli) {
					
				}
			}


				$this->set('clientes', $clientes3);
			$this->Tareasxclientesxestudio->recursive = 0;
			$tareascliente = $this->Tareasxclientesxestudio->find('all',array(
													'order' => 'Tareasxclientesxestudio.orden ASC',
													'conditions' => array(
										                'Tareasxclientesxestudio.estudio_id' => $this->Session->read('Auth.User.estudio_id') ,
										                'Tareasxclientesxestudio.estado' => 'habilitado' , 
										            ),
										            'contain'=>array('Tareascliente')));
			$this->set('tareas',$tareascliente);
			$mostrarInforme=true;
			$this->set('mostrarInforme',$mostrarInforme);
		}
		$conditionsImpuestos = array(
            	'Impuesto.estado' => 'habilitado' , 
			);
		$impuestos = $this->Impuesto->find('list',array(
				'conditions' =>$conditionsImpuestos,
				'order'=>array('Impuesto.orden'),
				'fields'=>array('Impuesto.id','Impuesto.nombre','Impuesto.organismo')
				)
			);	
			$this->set('impuestos',$impuestos);
		$conditionsGcli = array(
			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
            'Grupocliente.estado' => 'habilitado' , 
			);
		$gclis = $this->Grupocliente->find('list',array(
			'conditions' =>$conditionsGcli,
			'order'=>array('Grupocliente.nombre')
			)
		);	
		$conditionsCli = array(
							 'Grupocliente',
							 );
		$lclis = $this->Cliente->find('list',array(
									'contain' =>$conditionsCli,
									'conditions' => array(
							 			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
        								'Cliente.estado' => 'habilitado' , 
        								'Grupocliente.estado' => 'habilitado' , 
							 			),
									'order'=>array('Grupocliente.nombre','Cliente.nombre'),
									'fields'=>array('Cliente.id','Cliente.nombre','Grupocliente.nombre')
							 		)
								);
		$this->set(compact('lclis'));
		$this->set(compact('gclis'));
	}
	public function tareacargar($id=null,$periodo=null,$selectedBy=null){
		// PRIMERO CHEKIAR QUE EL CLIENTE QUE MUESTRA LAS VENTAS SEA PARTE DEL ESTUDIO ACTIVO

		$this->loadModel('Localidade');
		$this->loadModel('Partido');
		$this->loadModel('Grupocliente');
		$this->loadModel('Comprobante');
		$this->loadModel('Compra');
		$this->loadModel('Conceptostipo');
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
				   		'Venta'=>array(					   			
			   				'Puntosdeventa'=>array(
			   						'fields'=>array('id','nombre')
			   					),	
		   					'Subcliente'=>array(
		   						'fields'=>array('id','nombre','cuit')
		   					),	
		   					'Localidade'=>array(
		   						'Partido',
		   						'fields'=>array('id','nombre')
		   					),
		   					'Actividadcliente'=>array(
		   						'Actividade',
		   					),
		   					'Comprobante'=>array(
		   					),				   								   			
			   				'conditions' => array(					            	
					            	'Venta.periodo'=>$periodo					           
				   			),	
				   		),
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
						),
				   		'Conceptosrestante'=>array(			
				   			'Impcli'=>array(
				   				'Impuesto'=>array(
										'fields'=>array(
					   						'Impuesto.nombre'
					   					)
				   					)
				   				),
							'Partido'=>array(
								'fields'=>array(
									'Partido.nombre'
								)
							),
			   				'Localidade'=>array(
					   			'Partido'=>array(
					   				'fields'=>array(
											'Partido.nombre'
										)
					   				),
					   			),
				   			'Conceptostipo'=>array(
				   				'fields'=>array(
									'Conceptostipo.nombre','Conceptostipo.id'
									)
				   				),	
			   				'Comprobante'=>array(
				   				'fields'=>array(
					   						'Comprobante.nombre'
					   					)
				   				),			   			
			   				'conditions' => array(					            	
					            	'Conceptosrestante.periodo'=>$periodo		
					            	)			           
				   			),		
				   		'Compra'=>array(					   			
		   					'Provedore'=>array(
		   						'fields'=>array('id','nombre')
		   					),
		   					'Actividadcliente'=>array(
		   						'Actividade'
		   					),
		   					'Comprobante'=>array(
		   					),	
		   					'Localidade'=>array(
		   						'fields'=>array('id','nombre')
		   					),
		   					'Tipogasto'=>array(
		   						'fields'=>array('id','nombre')
		   					),				   								   			
			   				'conditions' => array(					            	
					            	'Compra.periodo'=>$periodo					           
				   			),	
				   		),	
				   		'Impcli'=>array(
							'Periodosactivo'=>array(
								'conditions'=>$conditionsImpCliHabilitados																								
							)
						)			   				   	
			   		),'conditions' => array(
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

	   	$this->set('cliente',$cliente);

	   	$optionspuntosdeventa = array(
	   		'conditions' =>array('Puntosdeventa.cliente_id' => $id,),
	   		'order'=>array()
	   	);
		$puntosdeventas = $this->Cliente->Puntosdeventa->find('list',$optionspuntosdeventa );	
		$this->set(compact('puntosdeventas'));

		$conditionsSubClientes = array(
			'Subcliente.cliente_id' => $id,);
		$subclientes = $this->Cliente->Subcliente->find('list',array(
													'conditions' =>$conditionsSubClientes,
													)
												);	
		$this->set(compact('subclientes'));

		$conditionsProvedores = array('Provedore.cliente_id' => $id,);
		$provedores = $this->Cliente->Provedore->find('list',array(
			'conditions' =>$conditionsProvedores,
			));	
		$this->set(compact('provedores'));

		$clienteActividadList=$this->Cliente->Actividadcliente->find('list', array(
												'contain' => array(
													'Actividade'
													),
											   'conditions' => array(
												                'Actividadcliente.cliente_id' => $id, 
												            ),
											   'fields' => array(
													'Actividadcliente.id','Actividade.nombre'
														)
											)
									);	
		$this->set('actividades', $clienteActividadList);
		
		$conditionsLocalidades = array(
			'contain'=>'Partido',
			'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
			'order'=>array('Partido.nombre','Localidade.nombre')
			);
		$localidades = $this->Localidade->find('list',$conditionsLocalidades);
		$this->set('localidades', $localidades);

		$conceptostipos = $this->Conceptostipo->find('list');
		$this->set('conceptostipos', $conceptostipos);
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
		$clienteImpuestosOptions = array(
					'contain'=>array(
							'Impuesto'=>array(
								'conditions'=> array(
									'AND'=>array(
										'Impcli.impuesto_id = Impuesto.id',
										'Impuesto.organismo <> "sindicato"',
										'Impuesto.organismo <> "banco"'
									)
								)
							)
						),
					'conditions' => array(
							'Impcli.cliente_id'=> $id
						), 
					'fields'=>array('Impcli.id','Impuesto.id'),
					'joins'=>array(
						array('table'=>'periodosactivos', 
			                 'alias' => 'Periodosactivo',
			                 'type'=>'inner',
			                 'conditions'=> array(
			                 	$conditionsImpCliHabilitadosImpuestos
			           			)
		                 	),
						),
						array('table'=>'impuesto',
							'alias' => 'Impuesto',
							'type'=>'inner',
							'conditions'=> array(
								'AND'=>array(
									'Impcli.impuesto_id = Impuesto.id',
									'Impuesto.organismo <> "sindicato"',
									'Impuesto.organismo <> "banco"'
								)
							)
						),
					);
		$impclisid=$this->Impcli->find('list',$clienteImpuestosOptions);
		$this->set('impclisid', $impclisid);

		$partidos = $this->Partido->find('list');
		$this->set('partidos', $partidos);

		//si es monotributista solo debe poder hacer facturas tipo C
		//sino mandar A y B 
		$optionsComprobantes=array();
		if($tieneMonotributo){
			$optionsComprobantes=array('conditions'=>array('Comprobante.tipo'=>'C'));
		}else{
			$optionsComprobantes=array('conditions'=>array('Comprobante.tipo'=>array('A','B')));
		}
		$comprobantes = $this->Comprobante->find('list',$optionsComprobantes);
		//esto se manda para poder buscar y comparar todos los tipos de comprobantes y usar sus datos
		$this->set('comprobantes', $comprobantes);
		$allcomprobantes = $this->Comprobante->find('all',array('contain'=>array()));
		$this->set('allcomprobantes', $allcomprobantes);
		//para Compras se deben mandar todos los comprobantes
		$comprobantesCompra = $this->Comprobante->find('list',array('contain'=>array()));
		$this->set('comprobantesCompra', $comprobantesCompra);

		$imputaciones=array(
			'Bs en Gral'=>'Bs en Gral',
			'Locaciones'=>'Locaciones',
			'Prest. Servicios'=>'Prest. Servicios',
			'Bs Uso'=>'Bs Uso',
			'Otros Conceptos'=>'Otros Conceptos',
			'Dcto 814'=>'Dcto 814');
		$this->set('imputaciones', $imputaciones);
		
		$optionsTipoGastos=array('conditions'=>array());
		$tipogastos = $this->Compra->Tipogasto->find('list',$optionsTipoGastos);
		$this->set('tipogastos', $tipogastos);

		$condicionesiva = array("monotributista" => 'Monotributista',"responsableinscripto" => 'Responsable Inscripto','consf/exento/noalcanza'=>"Cons. F/Exento/No Alcanza",);
		$this->set('condicionesiva', $condicionesiva);

		$alicuotas = array("0" => '0', "2.5" => '2.5', "5" => '5', "10.5" => '10.5', "21" => '21' , "27" => '27');
		$this->set('alicuotas', $alicuotas);
		
		$tipodebitos = array('Debito Fiscal'=>'Debito Fiscal','Bien de uso'=>'Bien de uso','Restitucion debito fiscal'=>'Restitucion debito fiscal');
		$this->set('tipodebitos', $tipodebitos);

		$tipocreditos = array('Credito Fiscal'=>'Credito Fiscal','Restitucion credito fiscal'=>'Restitucion credito fiscal');
		$this->set('tipocreditos', $tipocreditos);


		$conditionsGcli = array('Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),);
		$gclis = $this->Grupocliente->find('list',array('conditions' =>$conditionsGcli));

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
		$this->set(compact('gclis'));
	}
	function informepagosdelmes($data=null) {
		$pemes="";
		$peanio="";
		$this->loadModel('Grupocliente');

		$conditionsGcli = array(
			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
			'Grupocliente.estado' => 'habilitado'
			);
		$fields = array('id');
		$gclis = $this->Grupocliente->find('list',array('conditions' =>$conditionsGcli,'fields'=>$fields));	

		if ($this->request->is('post')) {
			$pemes=$this->request->data['clientes']['periodomes'];
			$peanio=$this->request->data['clientes']['periodoanio'];
			$this->set('periodomes', $pemes);
			$this->set('periodoanio', $peanio);

			$grupoclientesActual=$this->Grupocliente->find('all', array(
				   'contain'=>array(
				   		'Cliente'=>array(	
				   			'Deposito'=>array(
				   				'conditions' => array(
						            	'Deposito.periodo' => $pemes."-".$peanio
						            ),
				   				'order' => array('Cliente.nombre'),
				   			),	
				   			'Honorario'=>array(
					   				'conditions' => array(
						            	'Honorario.periodo' => $pemes."-".$peanio
							            ),
					   			),		   							   							   	
					   		'Impcli'=>array(
						         'Impuesto'=>array(
						            'fields'=>array('id','nombre','lugarpago'),						             
						         ),
					        	 'Eventosimpuesto'=>array( 
					        	  'conditions' => array(
							            	 'Eventosimpuesto.periodo' => $pemes."-".$peanio
							            ),
            						'order' => array('fchvto' => 'ASC')
					        	  ),
					       	),
				   			'conditions'=>array('Cliente.estado' => 'habilitado')					       	
			   			),
				   		
				   	),
				   'conditions' => array(
					                'Grupocliente.id' => $gclis ,
									'Grupocliente.estado' => 'habilitado'
					            ),
				   	'order' => array('Grupocliente.nombre'),

			   )
			);
			$grupoclientesHistorial=$this->Grupocliente->find('all', array(
				   'contain'=>array(
				   		'Cliente'=>array(
				   			'Deposito'=>array(
				   				'conditions' => array(						            	 
						            	 'OR'=>array(
							            		'SUBSTRING(Deposito.periodo,4,7)*1 < '.$peanio.'*1',
							            		'AND'=>array(
							            			'SUBSTRING(Deposito.periodo,4,7)*1 <= '.$peanio.'*1',
							            			'SUBSTRING(Deposito.periodo,1,2) < '.$pemes.'*1'
							            			),
							            		),
						            ),
				   					'order' => array('Cliente.nombre'),
				   			),
					   		'Honorario'=>array(
					   				'conditions' => array(
					   						'OR'=>array(
								            		'SUBSTRING(Honorario.periodo,4,7)*1 < '.$peanio.'*1',
								            		'AND'=>array(
								            			'SUBSTRING(Honorario.periodo,4,7)*1 <= '.$peanio.'*1',
								            			'SUBSTRING(Honorario.periodo,1,2) < '.$pemes.'*1'
								            			),
								            		),
							            ),
					   			),	
					   		'Impcli'=>array(					         
					        	 'Eventosimpuesto'=>array(
					        	  'conditions' => array(
			        	  					'OR'=>array(
							            		'SUBSTRING(Eventosimpuesto.periodo,4,7)*1 < '.$peanio.'*1',
							            		'AND'=>array(
							            			'SUBSTRING(Eventosimpuesto.periodo,4,7)*1 <= '.$peanio.'*1',
							            			'SUBSTRING(Eventosimpuesto.periodo,1,2) < '.$pemes.'*1'
							            			),
							            		),
							            ),
					        	  ),
					       	),
				   			'conditions'=>array('Cliente.estado' => 'habilitado')					       						       	
			   			),
				   		
				   	),
				   'conditions' => array(
					                'Grupocliente.id' => $gclis, 
									'Grupocliente.estado' => 'habilitado'
					            ),

			   )
			);		
			$this->set('grupoclientesActual', $grupoclientesActual);
			$this->set('grupoclientesHistorial', $grupoclientesHistorial);
			$mostrarInforme=true;
			$this->set('mostrarInforme',$mostrarInforme);
		}
	}
	public function pagosdelmes($data=null) {
		$pemes="";
		$peanio="";
		$this->loadModel('Grupocliente');
				
		if ($this->request->is('post')) {
			$gclis = $this->request->data['clientes']['gclis'];
			$pemes=$this->request->data['clientes']['periodomes'];
			$peanio=$this->request->data['clientes']['periodoanio'];
			$this->set('periodomes', $pemes);
			$this->set('periodoanio', $peanio);

			$grupoclientesActual=$this->Grupocliente->find('all', array(
				   'contain'=>array(
				   		'Cliente'=>array(	
			   				'order' => array('Cliente.nombre'),
			   				'conditions'=>array('Cliente.estado'=>'habilitado'),
				   			'Deposito'=>array(
				   				'conditions' => array(
						            	'Deposito.periodo' => $pemes."-".$peanio
						            ),
				   			),	
				   			'Honorario'=>array(
					   				'conditions' => array(
						            	'Honorario.periodo' => $pemes."-".$peanio
							            ),
					   			),		   							   							   	
					   		'Impcli'=>array(
						         'Impuesto'=>array(
						            'fields'=>array('id','nombre','lugarpago'),						             
						         ),
					        	 'Eventosimpuesto'=>array( 
					        	  'conditions' => array(
							            	 'Eventosimpuesto.periodo' => $pemes."-".$peanio
							            ),
            						
					        	  ),
					       	),
			   			),
				   		
				   	),
				   'conditions' => array(
					                'Grupocliente.id' => $gclis, 
					                'Grupocliente.estado' => 'habilitado' 
					            ),
				   	'order' => array('Grupocliente.nombre'),

			   )
			);
			$grupoclientesHistorial=$this->Grupocliente->find('all', array(
				   'contain'=>array(
				   		'Cliente'=>array(
				   			'order' => array('Cliente.nombre'),
				   			'conditions' => array('Cliente.estado' => 'habilitado'),
					                 
				   			'Deposito'=>array(
				   				'conditions' => array(						            	 
						            	 'OR'=>array(
							            		'SUBSTRING(Deposito.periodo,4,7)*1 < '.$peanio.'*1',
							            		'AND'=>array(
							            			'SUBSTRING(Deposito.periodo,4,7)*1 <= '.$peanio.'*1',
							            			'SUBSTRING(Deposito.periodo,1,2) < '.$pemes.'*1'
							            			),
							            		),
						            ),
				   			),
					   		'Honorario'=>array(
					   				'conditions' => array(
					   						'OR'=>array(
								            		'SUBSTRING(Honorario.periodo,4,7)*1 < '.$peanio.'*1',
								            		'AND'=>array(
								            			'SUBSTRING(Honorario.periodo,4,7)*1 <= '.$peanio.'*1',
								            			'SUBSTRING(Honorario.periodo,1,2) < '.$pemes.'*1'
								            			),
								            		),
							            ),
					   			),	
					   		'Impcli'=>array(					         
					        	 'Eventosimpuesto'=>array(
					        	  'conditions' => array(
			        	  					'OR'=>array(
							            		'SUBSTRING(Eventosimpuesto.periodo,4,7)*1 < '.$peanio.'*1',
							            		'AND'=>array(
							            			'SUBSTRING(Eventosimpuesto.periodo,4,7)*1 <= '.$peanio.'*1',
							            			'SUBSTRING(Eventosimpuesto.periodo,1,2) < '.$pemes.'*1'
							            			),
							            		),
							            ),
					        	  ),
					       	),
			   			),
				   		
				   	),
				   'conditions' => array(
					                'Grupocliente.id' => $gclis, 
					                'Grupocliente.estado' => 'habilitado', 
					            ),

			   )
			);		
			$this->set('grupoclientesActual', $grupoclientesActual);
			$this->set('grupoclientesHistorial', $grupoclientesHistorial);
			$mostrarInforme=true;
			$this->set('mostrarInforme',$mostrarInforme);
		}
		$conditionsGcli = array(
			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
			'Grupocliente.estado' => 'habilitado',
			);
		$gclis = $this->Grupocliente->find('list',array(
			'conditions' =>$conditionsGcli,
			'order'=>array('Grupocliente.nombre')
			)
		);		
		$this->set(compact('gclis'));
	}
	public function informefinancierotributario($data=null) {
		$this->loadModel('Impcli');
		$this->loadModel('Tareasxclientesxestudios');
		$this->loadModel('Tareasimpuesto');
		$this->loadModel('Grupocliente');
		$pemes="";
		$peanio="";
		if ($this->request->is('post')) {
			$pemes=$this->request->data['clientes']['periodomes'];
			$peanio=$this->request->data['clientes']['periodoanio'];
			$this->set('periodomes', $pemes);
			$this->set('periodoanio', $peanio);

			$grupoclientesActual=$this->Grupocliente->find('all', array(
				   'contain'=>array(
				   		'Cliente'=>array(
				   			'Domicilio',
				   			'Deposito'=>array(
				   				'conditions' => array(
						            	 'Deposito.periodo' => $pemes."-".$peanio,
						            ),
				   			),
				   			'Venta'=>array(
				   				'conditions' => array(
						            	 'Venta.periodo' => $pemes."-".$peanio
						            ),
				   			),
				   			'Compra'=>array(
				   				'conditions' => array(
						            	 'Compra.periodo' => $pemes."-".$peanio
						            ),
				   			),
				   			'Sueldo'=>array(
				   				'conditions' => array(
						            	 'Sueldo.periodo' => $pemes."-".$peanio
						            ),
				   			),
					   		'Honorario'=>array(
					   				'conditions' => array(
							            	 'Honorario.periodo' => $pemes."-".$peanio
							            ),
					   			),	
					   		'Impcli'=>array(
						         'Impuesto'=>array(
						            'fields'=>array('id','nombre','abreviacion','lugarpago','descripcion'),
						         ),
					        	 'Eventosimpuesto'=>array( 
					        	  'conditions' => array(
							            	 'Eventosimpuesto.periodo' => $pemes."-".$peanio
							            ),
					        	  ),
					       	),
					       	'Plandepago'=>array(
				   				'conditions' => array(
						            	 'Plandepago.periodo' => $pemes."-".$peanio
						            ),
				   				),
					       	'order' => array('Cliente.nombre'),
					       	'conditions'=>array(
				       			'OR'=>array(
				            		array('Cliente.fchfincliente'=>null),
				            		array('Cliente.fchfincliente'=>""),												            		
					       			'OR'=>array(
					            		'SUBSTRING(Cliente.fchfincliente,4,7)*1 > '.$peanio.'*1',
					            		'AND'=>array(
					            			'SUBSTRING(Cliente.fchfincliente,4,7)*1 >= '.$peanio.'*1',
					            			'SUBSTRING(Cliente.fchfincliente,1,2) >= '.$pemes.'*1'
					            			),
					            		),
					       			)
					       		),
			   			),			   				
				   		
				   	),
				   'conditions' => array(
					                'Grupocliente.id' => $this->request->data['clientes']['gclis']  ,
					                'Grupocliente.estado' => 'habilitado'  ,
					            ),
				   'order' => array('Grupocliente.nombre'),

			   )
			);
			$grupoclientesHistorial=$this->Grupocliente->find('all', array(
				   'contain'=>array(
				   		'Cliente'=>array(
				   			'Deposito'=>array(
				   				'conditions' => array(						            	 
						            	 'OR'=>array(
							            		'SUBSTRING(Deposito.periodo,4,7)*1 < '.$peanio.'*1',
							            		'AND'=>array(
							            			'SUBSTRING(Deposito.periodo,4,7)*1 <= '.$peanio.'*1',
							            			'SUBSTRING(Deposito.periodo,1,2) < '.$pemes.'*1'
							            			),
							            		),
						            ),
				   			),
				   			'Venta'=>array(
				   				'conditions' => array(						            	 
						            	 'OR'=>array(
							            		'SUBSTRING(Venta.periodo,4,7)*1 < '.$peanio.'*1',
							            		'AND'=>array(
							            			'SUBSTRING(Venta.periodo,4,7)*1 <= '.$peanio.'*1',
							            			'SUBSTRING(Venta.periodo,1,2) < '.$pemes.'*1'
							            			),
							            		),
						            ),
				   			),
				   			'Compra'=>array(
				   				'conditions' => array(						            	 
						            	 'OR'=>array(
							            		'SUBSTRING(Compra.periodo,4,7)*1 < '.$peanio.'*1',
							            		'AND'=>array(
							            			'SUBSTRING(Compra.periodo,4,7)*1 <= '.$peanio.'*1',
							            			'SUBSTRING(Compra.periodo,1,2) < '.$pemes.'*1'
							            			),
							            		),
						            ),
				   			),
				   			'Sueldo'=>array(
				   				'conditions' => array(						            	 
						            	 'OR'=>array(
							            		'SUBSTRING(Sueldo.periodo,4,7)*1 < '.$peanio.'*1',
							            		'AND'=>array(
							            			'SUBSTRING(Sueldo.periodo,4,7)*1 <= '.$peanio.'*1',
							            			'SUBSTRING(Sueldo.periodo,1,2) < '.$pemes.'*1'
							            			),
							            		),
						            ),
				   			),
					   		'Honorario'=>array(
					   				'conditions' => array(
					   						'OR'=>array(
								            		'SUBSTRING(Honorario.periodo,4,7)*1 < '.$peanio.'*1',
								            		'AND'=>array(
								            			'SUBSTRING(Honorario.periodo,4,7)*1 <= '.$peanio.'*1',
								            			'SUBSTRING(Honorario.periodo,1,2) < '.$pemes.'*1'
								            			),
								            		),
							            ),
					   			),	
					   		'Impcli'=>array(					         
					        	 'Eventosimpuesto'=>array(
					        	  'conditions' => array(
			        	  					'OR'=>array(
							            		'SUBSTRING(Eventosimpuesto.periodo,4,7)*1 < '.$peanio.'*1',
							            		'AND'=>array(
							            			'SUBSTRING(Eventosimpuesto.periodo,4,7)*1 <= '.$peanio.'*1',
							            			'SUBSTRING(Eventosimpuesto.periodo,1,2) < '.$pemes.'*1'
							            			),
							            		),
							            ),
					        	  ),
					       	),
					       	'Plandepago'=>array(
				   				'conditions' => array(
			        	  					'OR'=>array(
							            		'SUBSTRING(Plandepago.periodo,4,7)*1 < '.$peanio.'*1',
							            		'AND'=>array(
							            			'SUBSTRING(Plandepago.periodo,4,7)*1 <= '.$peanio.'*1',
							            			'SUBSTRING(Plandepago.periodo,1,2) < '.$pemes.'*1'
							            			),
							            		),
							            ),
				   				),
					       	'order' => array('Cliente.nombre'),
					       	//'conditions'=>array('Cliente.estado' => 'habilitado'  ,),
			   			),
				   		
				   	),
				   'conditions' => array(
					                'Grupocliente.id' => $this->request->data['clientes']['gclis'] , 
					                'Grupocliente.estado' => 'habilitado'  ,
					            ),

			   )
			);			
			
			$this->set('grupoclientesActual', $grupoclientesActual);
			$this->set('grupoclientesHistorial', $grupoclientesHistorial);
					
			$mostrarInforme=true;
			$this->set('mostrarInforme',$mostrarInforme);
			//return $this->redirect(array('action' => 'avance'));
		}

		$conditionsGcli = array(
			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
            'Grupocliente.estado' => 'habilitado'  ,
			);
		$gclis = $this->Grupocliente->find('list',array(
			'conditions' =>$conditionsGcli,
			'order'=>array('Grupocliente.nombre')
			)
		);	
		$this->set(compact('gclis'));
	}
	public function comparativo(){
		$this->loadModel('Grupocliente');

		//para seleccion de clientes
		$conditionsCli = array(
							 'Grupocliente',
							 );
		$clienteses = $this->Grupocliente->find('list',array(
									'conditions' => array(
							 			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
            							'Grupocliente.estado' => 'habilitado'  ,
							 							),
									'order'=>array(
										'Grupocliente.nombre'
									)
								)

		);
		$this->set('gclis',$clienteses);
	}
	public function comparativolistacliente(){
		$clientes3=$this->Cliente->find('all', array(
		   'contain'=>array(							      							     
		      'Impcli'=>array(
		         'Impuesto'=>array(
		            'fields'=>array('id','nombre'),
		             'conditions'=>array( )
		         ),
	        	 'Eventosimpuesto'=>array(
	        	 	'conditions' => array(
	        	 		'Eventosimpuesto.periodo' => $this->request->data['clientes']['periodomes']."-".$this->request->data['clientes']['periodoanio']
	        	 		),
	        	 	'fields'=>array('id','impcli_id','monc','periodo','montovto'),
	        	 	), 						        	 
	        	 'conditions' => array(
	        	 	'OR'=>array(
			            	//'Impcli.id'=>$impuestoshabilitados,
			            	)
			            ),
	        	 'fields'=>array('id','cliente_id','impuesto_id'),
				)									       
		    ),
		   'conditions' => array(
		   					'Cliente.grupocliente_id'=>$this->request->data['clientes']['gclis'],
		   					'Cliente.estado'=>'habilitado',
		   					),
		   'fields'=>array('id','nombre','grupocliente_id'),
		   'order' => array(
			                'Cliente.nombre'  
			            ),

		));
		$this->set('clientes', $clientes3);
		$this->set('shownombre', $this->request->data['clientes']['shownombre']);
		$this->autoRender=false; 				
		$this->layout = 'ajax';
		$this->render('comparativolistacliente');
	}
	public function view($id = null) {
		$this->loadModel('Impuesto');
		$this->loadModel('Localidade');
		$this->loadModel('Partido');
		$this->loadModel('Puntosdeventa');
		$this->loadModel('Subcliente');
		$this->loadModel('Actividade');
		$this->loadModel('ActividadCliente');
		$this->loadModel('Domicilio');
		$this->loadModel('Conveniocolectivotrabajo');

		if(!is_null($id)){
			$containCliAuth = array(
								 'Grupocliente',
								 );
			$clientesAuth = $this->Cliente->find('all',array(
										'contain' =>$containCliAuth,
										'conditions' => array(
								 			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
								 			'Cliente.id' => $id ,
								 			'Cliente.estado' => 'habilitado' ,
								 		)));
			$numClis = sizeof($clientesAuth);
			if($numClis==0){
				$this->Session->setFlash(__('Cliente No existente. Alerta enviada.'));
				return $this->redirect(array('controller'=>'users','action' => 'index'));
			}
			if (!$this->Cliente->exists($id)) {
				$this->Session->setFlash(__('Cliente No existente '.$numClis));
				return $this->redirect(array('controller'=>'users','action' => 'index'));
			}
			$clientes3=$this->Cliente->find('first', array(
										   'contain'=>array(
										      	'Grupocliente',
											      'Organismosxcliente',
											      'Domicilio'=>array(
											      	'Localidade'=>array(
											      			'Partido'
											      		)
											      	),
											      'Personasrelacionada',
												  'Subcliente',
												  'Provedore',
												  'Empleado',
	  										      'Venta'=>array(
										        	 'Subcliente', 
											       ),
	  										      'Actividadcliente'=>array(
										      		 'Actividade'
	  										      	),
	  										      'Puntosdeventa'=>array(
	  										      	'Domicilio'=>array(
	  										      		'Localidade'=>array(
	  										      			'Partido'
	  										      			)
	  										      		),
	  										      	'order'=>array('Puntosdeventa.nombre')
											      	),
											      'Impcli'=>array(
											         'Impuesto'=>array(
											            'fields'=>array('id','nombre','organismo'),								             
											         ),
										        	 'Eventosimpuesto', 
										        	 'Periodosactivo'=>array(
	 														'conditions' => array(
												                'OR'=>array(
										                			'Periodosactivo.hasta' => '', 
																	'Periodosactivo.hasta IS NULL',
									                			)
												            ),
										        	 	), 
											       ),
											      'Deposito',
											      'Honorario',
										    ),
										   'conditions' => array(
											                'Cliente.id' => $id, // <-- Notice this addition
								 							'Cliente.estado' => 'habilitado' ,
											            ),
										));	
			$this->set('cliente', $clientes3);
							
			$resAfip = $this->Impuesto->find('all', 
				array(
				    'contain' => array(
				    	'Impcli' => array(
					    	'Periodosactivo'=>array(
								'conditions' => array(
				                	'OR'=>array(
			                			'Periodosactivo.hasta' => '', 
										'Periodosactivo.hasta IS NULL',
		                			)				                	
					            ),
		        	 		), 
		        	 		'conditions' => array(
					                'cliente_id' => $id, 
					            ),
				    	),
	    			),
	    			'conditions' => array(
					                'organismo' => 'afip',
					            ),
				)
			);
			$this->set('impuestosafip', $resAfip);

			$resDGR = $this->Impuesto->find('all',array(
				    'contain' => array(
				    	'Impcli' => array(
					    	'Periodosactivo'=>array(
								'conditions' => array(
					                'OR'=>array(
			                			'Periodosactivo.hasta' => '', 
										'Periodosactivo.hasta IS NULL',
		                			)
					            ),
		        	 		), 
		        	 		'conditions' => array(
					                'cliente_id' => $id, 
					            ),
				    	),
	    			),
	    			'conditions' => array(
					                'organismo' => 'dgr',
					            ),
				)
			);
			$this->set('impuestosdgr', $resDGR);
			
			$resDGRM = $this->Impuesto->find('all',array(
				    'contain' => array(
				    	'Impcli' => array(
					    	'Periodosactivo'=>array(
								'conditions' => array(
					                'OR'=>array(
			                			'Periodosactivo.hasta' => '', 
										'Periodosactivo.hasta IS NULL',
		                			)
					            ),
		        	 		), 
		        	 		'conditions' => array(
					                'cliente_id' => $id, 
					            ),
				    	),
	    			),
	    			'conditions' => array(
					                'organismo' => 'dgrm',
					            ),
				)
			);
			$this->set('impuestosdgrm', $resDGRM);

			$resSINDICATO = $this->Impuesto->find('all',array(
				    'contain' => array(
				    	'Impcli' => array(
					    	'Periodosactivo'=>array(
								'conditions' => array(
					                'OR'=>array(
			                			'Periodosactivo.hasta' => '', 
										'Periodosactivo.hasta IS NULL',
		                			)
					            ),
		        	 		), 
		        	 		'conditions' => array(
					                'cliente_id' => $id, 
					            ),
				    	),
	    			),
	    			'conditions' => array(
					                'organismo' => 'sindicato',
					            ),
				)
			);
			$this->set('impuestossindicato', $resSINDICATO);

			$resBANCO = $this->Impuesto->find('all',array(
				    'contain' => array(
				    	'Impcli' => array(
					    	'Periodosactivo'=>array(
								'conditions' => array(
									'OR'=>array(
			                			'Periodosactivo.hasta' => '', 
										'Periodosactivo.hasta IS NULL',
		                			)
					            )
		        	 		), 
		        	 		'conditions' => array(
					                'cliente_id' => $id, 
					            ),
				    	),
	    			),
	    			'conditions' => array(
					                'organismo' => 'banco',
					            ),
				)
			);
			$this->set('impuestosbancos', $resBANCO);

			$options = array(
                'conditions' => array('Cliente.' . $this->Cliente->primaryKey => $id),
                'contain'=>array()
            );
			$this->request->data = $this->Cliente->find('first', $options);

			$partidos = $this->Partido->find('list');
			$this->set('partidos', $partidos);

			$optionsLoc = array(
					'contain'=>array('Partido'),
					'conditions' => array( ),
					'fields'=> array('Localidade.id','Localidade.nombre','Partido.nombre'),
					'order'=>array('Partido.nombre','Localidade.nombre')
			);			

			$localidades = $this->Localidade->find('list',$optionsLoc);
			$this->set('localidades', $localidades);
			
			$optionSisFact=array(
				'Controlador Fiscal'=>'Controlador Fiscal',
				'Factuweb'=>'Factuweb (Imprenta) ',
				'RECE'=>'RECE para aplicativo y web services',
				'Factura en Linea'=>'Factura en Linea'
				);
			$this->set('optionSisFact', $optionSisFact);

			$clienteActividadList = $this->Cliente->Actividadcliente->find('list', array(
											   'conditions' => array(
												                'Actividadcliente.cliente_id' => $id, 
												            ),
											   'fields' => array(
													'Actividadcliente.actividade_id'
														)
											)
									);	
			$this->set('clienteActividadList', $clienteActividadList);
			$optionsAct = array(
					'conditions' => array(
							'NOT' => array('Actividade.id' => $clienteActividadList)
						),
					'order'=> array('Actividade.descripcion')
					);

			$actividades = $this->Actividade->find('list',$optionsAct);

			$this->set('actividades', $actividades);

			$optionsPdV = array('conditions' => array('Puntosdeventa.cliente_id' => $id));
			$puntosdeventa = $this->Puntosdeventa->find('list',$optionsPdV);
			$this->set('puntosdeventas', $puntosdeventa);

			$conveniocolectivotrabajos = $this->Conveniocolectivotrabajo->find('list');
			$this->set('conveniocolectivotrabajos', $conveniocolectivotrabajos);

			$optionsDoc = array(
								'conditions' => array('Domicilio.cliente_id'=>$id ),
						);			
			$domicilios = $this->Domicilio->find('list',$optionsDoc);
			$this->set('domicilios', $domicilios);

			$optionsSC = array('conditions' => array('Subcliente.cliente_id' => $id));
			$subcliente = $this->Subcliente->find('list',$optionsSC);
			$this->set('subclientes', $subcliente);

			$conditionsGcli = array(
				'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
				'Grupocliente.estado' => 'habilitado',
				);
			$grupoclientes = $this->Cliente->Grupocliente->find('list',array('conditions' =>$conditionsGcli));

			$categoriasmonotributos = array('B'=>'B','C'=>'C','D'=>'D','E'=>'E','F'=>'F','G'=>'G','H'=>'H','I'=>'I','J'=>'J','K'=>'K','L'=>'L');
			$this->set(compact('grupoclientes','categoriasmonotributos'));
			$mostrarView=true;

		}else{
			$mostrarView=false;
		}

		//for index
		$conditionsCli = array(
							 'Grupocliente',
							 );
		$clienteses = $this->Cliente->find('all',array(
									'contain' =>$conditionsCli,
									'conditions' => array(
							 			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
							 			'Grupocliente.estado' => 'habilitado',
							 			'Cliente.estado'=>'habilitado'
							 							),
									'order'=>array('Grupocliente.nombre','Cliente.nombre')
									)

		);
		$this->set('clienteses',$clienteses);

		$clientesesDeshabilitados = $this->Cliente->find('all',array(
									'contain' =>$conditionsCli,
									'conditions' => array(
							 			'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
							 			'Cliente.estado'=>'deshabilitado'
							 							),
									'order'=>array('Grupocliente.nombre','Cliente.nombre')
									)
		);
		$this->set('clientesesDeshabilitados',$clientesesDeshabilitados);

		$this->set('mostrarView',$mostrarView);
	}
	public function habilitar($id=null) {
		$this->Cliente->id = $id;		
		if($this->Cliente->saveField('estado', 'habilitado')&&$this->Cliente->saveField('fchfincliente', '')){
			$this->Session->setFlash(__('El cliente a sido habilitado.'));
		}else{
			$this->Session->setFlash(__('El cliente NO a sido habilitado.'));
		}
		$this->redirect(array('action' => 'view'));	
	}
	public function deshabilitar($id=null) {
		$this->loadModel('Honorario');
		$this->loadModel('Deposito');
		$this->loadModel('Eventosimpuesto');
		$this->loadModel('Eventoscliente');
		$this->Cliente->id = $id;	
		$añoDefault="";
		$mesDefault="";
		//vamos a tener que buscar el ultimo honorario creado, el ultimo deposito y el ultmo eventocliente/evento impuesto
		//y sacar la fechas mas alta de todos estos registros ya que a partir de esa fecha(periodo+1)en adelante el cliente ya no saldra en 
		//el informe tributario financiero
		//buscamos honorario
		$honorarioOptions = array(
		    'conditions' => array('Honorario.cliente_id' => $id), 
		    'fields' => array('Honorario.id','MAX(CONCAT(SUBSTRING(Honorario.periodo,4,7),SUBSTRING(Honorario.periodo,1,2))) AS mperiodo','Honorario.periodo'), 
		    'group' => 'Honorario.cliente_id'
		    );
		
		$fechaguardada="nohabia";
		$mihonorario=$this->Honorario->find('first',$honorarioOptions);
		if(isset($mihonorario[0]['mperiodo']) && $mihonorario[0]['mperiodo']!='' && $mihonorario[0]['mperiodo']!=null){
			$pemes = substr($mihonorario[0]['mperiodo'], 4);
			$peanio = substr($mihonorario[0]['mperiodo'], 0,4);
			$mesDefault=$pemes;
			$añoDefault=$peanio;
			$fechaguardada.=$mesDefault.$añoDefault.'Hono';
		}
		$this->set('mihonorario',$mihonorario);

		//buscamos depositos
		$depositosOptions = array(
		    'conditions' => array('Deposito.cliente_id' => $id), 
		    'fields' => array('Deposito.id','MAX(CONCAT(SUBSTRING(Deposito.periodo,4,7),SUBSTRING(Deposito.periodo,1,2))) AS mperiodo','Deposito.periodo'), 
		    'group' => 'Deposito.id'
		    );
		$mideposito=$this->Deposito->find('first',$depositosOptions);
		if(isset($mideposito[0]['mperiodo']) && $mideposito[0]['mperiodo']!='' && $mideposito[0]['mperiodo']!=null){

			$pemes = substr($mideposito[0]['mperiodo'], 4);
			$peanio = substr($mideposito[0]['mperiodo'], 0,3);
			
			if($añoDefault<$peanio){
				$this->set('midepositocompraracion',$añoDefault.'<'.$peanio);

				$mesDefault=$pemes;
				$añoDefault=$peanio;
				$fechaguardada.=$mesDefault.$añoDefault.'Depo';
				
			}else if($añoDefault=$peanio&&$mesDefault<$pemes){
				$this->set('mesmidepositocompraracion',$añoDefault.'<'.$peanio);
				$mesDefault=$pemes;
				$fechaguardada.=$mesDefault.$añoDefault.'Depo';
			}
		}
		$this->set('mideposito',$mideposito);

		//buscamos EventoImpuesto
		$eventosimpuestosOptions = array(
			'contain'=>array('Impcli'),
		    'conditions' => array('Impcli.cliente_id' => $id), 
		    'fields' => array('Eventosimpuesto.id','MAX(Eventosimpuesto.periodo) AS periodo'), 
		    'fields' => array('Eventosimpuesto.id','MAX(CONCAT(SUBSTRING(Eventosimpuesto.periodo,4,7),SUBSTRING(Eventosimpuesto.periodo,1,2))) AS mperiodo','Eventosimpuesto.periodo'), 
		    'group' => 'Eventosimpuesto.id'
		    );
		$mieventoimpuesto=$this->Eventosimpuesto->find('first',$eventosimpuestosOptions);
		if(isset($mieventoimpuesto[0]['mperiodo']) && $mieventoimpuesto[0]['mperiodo']!='' && $mieventoimpuesto[0]['mperiodo']!=null){
			$pemes = substr($mieventoimpuesto[0]['mperiodo'], 4);
			$peanio = substr($mieventoimpuesto[0]['mperiodo'], 0,4);
			if($añoDefault<$peanio){
				$this->set('mieventoimpuestocompraracion',$añoDefault.'<'.$peanio);
				$mesDefault=$pemes;
				$añoDefault=$peanio;
				$fechaguardada.=$mesDefault.$añoDefault.'impuesto';
			}else if($añoDefault=$peanio&&$mesDefault<$pemes){
				$this->set('mesmieventoimpuestocompraracion',$añoDefault.'<'.$peanio);
				$mesDefault=$pemes;
				$fechaguardada.=$mesDefault.$añoDefault.'impuesto';
			}
		}
		$this->set('mieventoimpuesto',$mieventoimpuesto);

		$eventosclientesOptions = array(
		    'conditions' => array('Eventoscliente.cliente_id' => $id), 
		    'fields' => array('Eventoscliente.id','MAX(CONCAT(SUBSTRING(Eventoscliente.periodo,4,7),SUBSTRING(Eventoscliente.periodo,1,2))) AS mperiodo','Eventoscliente.periodo'), 
		    'group by' => 'Eventoscliente.id'
		    );
		$mieventocliente=$this->Eventoscliente->find('first',$eventosclientesOptions);
		if(isset($mieventocliente[0]['mperiodo']) && $mieventocliente[0]['mperiodo']!='' && $mieventocliente[0]['mperiodo']!=null){
			$pemes = substr($mieventocliente[0]['mperiodo'], 4);
			$peanio = substr($mieventocliente[0]['mperiodo'], 0,4);
			if($añoDefault<$peanio){
				$this->set('mieventoclientecompraracion',$añoDefault.'<'.$peanio);
				$mesDefault=$pemes;
				$añoDefault=$peanio;
				$fechaguardada.=$mesDefault.$añoDefault.'cliente';
			}else if($añoDefault=$peanio&&$mesDefault<$pemes){
				$this->set('mesmieventoclientecompraracion',$añoDefault.'<'.$peanio);
				$mesDefault=$pemes;
				$fechaguardada.=$mesDefault.$añoDefault.'cliente';
			}
		}
		$this->set('mieventocliente',$mieventocliente);

		$this->set('fechaguardada',$fechaguardada);
		if($añoDefault==""){
			$añoDefault=date('Y');
			$mesDefault=date('m');
		}
		$this->set('fchfincliente',$mesDefault.'-'.$añoDefault);

		if($this->Cliente->saveField('estado', 'deshabilitado')&&$this->Cliente->saveField('fchfincliente', $mesDefault.'-'.$añoDefault)){
			$this->Session->setFlash(__('El cliente a sido deshabilitado.'));
		}else{
			$this->Session->setFlash(__('El cliente NO a sido deshabilitado	.'));
		}
		$this->redirect(array('action' => 'view'));	
	}
	public function add() {
		$this->loadModel('Organismosxcliente');
		if ($this->request->is('post')) {
			$this->Cliente->create();

			//if($this->request->data['Cliente']['fchcorteejerciciofiscal']!="")
			//$this->request->data('Cliente.fchcorteejerciciofiscal',date('Y-m-d',strtotime($this->request->data['Cliente']['fchcorteejerciciofiscal'])));
			if($this->request->data['Cliente']['fchcumpleanosconstitucion']!="")
				$this->request->data('Cliente.fchcumpleanosconstitucion',date('Y-m-d',strtotime($this->request->data['Cliente']['fchcumpleanosconstitucion'])));
			//if($this->request->data['Cliente']['inscripcionregistrocomercio']!="")
			//	$this->request->data('Cliente.inscripcionregistrocomercio',date('Y-m-d',strtotime($this->request->data['Cliente']['inscripcionregistrocomercio'])));
			if($this->request->data['Cliente']['fchiniciocliente']!="")
				$this->request->data('Cliente.fchiniciocliente',date('Y-m-d',strtotime($this->request->data['Cliente']['fchiniciocliente'])));
			if($this->request->data['Cliente']['fchfincliente']!="")
				$this->request->data('Cliente.fchfincliente',date('Y-m-d',strtotime($this->request->data['Cliente']['fchfincliente'])));
			//if($this->request->data['Cliente']['vtocaia']!="")
			//	$this->request->data('Cliente.vtocaia',date('Y-m-d',strtotime($this->request->data['Cliente']['vtocaia'])));
			//if($this->request->data['Cliente']['vtocaib']!="")
			//	$this->request->data('Cliente.vtocaib',date('Y-m-d',strtotime($this->request->data['Cliente']['vtocaib'])));
			//if($this->request->data['Cliente']['vtocaic']!="")
			//	$this->request->data('Cliente.vtocaic',date('Y-m-d',strtotime($this->request->data['Cliente']['vtocaic'])));

			if ($this->Cliente->save($this->request->data)) {
				$this->Session->setFlash(__('El cliente a sido guardado	.'));

				//Debemos crear 
				$this->Organismosxcliente->create();
				$this->Organismosxcliente->set('cliente_id',$this->Cliente->getLastInsertID());
				$this->Organismosxcliente->set('tipoorganismo','afip');
				$this->Organismosxcliente->save();

				$this->Organismosxcliente->create();
				$this->Organismosxcliente->set('cliente_id',$this->Cliente->getLastInsertID());
				$this->Organismosxcliente->set('tipoorganismo','dgr');
				$this->Organismosxcliente->save();

				$this->Organismosxcliente->create();
				$this->Organismosxcliente->set('cliente_id',$this->Cliente->getLastInsertID());
				$this->Organismosxcliente->set('tipoorganismo','dgrm');
				$this->Organismosxcliente->save();

				$this->Organismosxcliente->create();
				$this->Organismosxcliente->set('cliente_id',$this->Cliente->getLastInsertID());
				$this->Organismosxcliente->set('tipoorganismo','sindicato');
				$this->Organismosxcliente->save();

				$this->Organismosxcliente->create();
				$this->Organismosxcliente->set('cliente_id',$this->Cliente->getLastInsertID());
				$this->Organismosxcliente->set('tipoorganismo','banco');
				$this->Organismosxcliente->save();

				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('El cliente no pudo ser guardado. Por favor, intente de nuevo mas tarde.'));
			}
		}
		$conditionsGcli = array('Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),);
		$grupoclientes = $this->Cliente->Grupocliente->find('list',array('conditions' =>$conditionsGcli));
		$this->set(compact('grupoclientes'));
	}
	public function edit(){	
		$this->autoRender=false; 
		if($this->RequestHandler->isAjax()){ 
			Configure::write('debug', 0); } 
		if(!empty($this->data)){ 
			if (isset($this->request->data['Cliente']['fchcumpleanosconstitucion']))
				if($this->request->data['Cliente']['fchcumpleanosconstitucion']!="")
					$this->request->data('Cliente.fchcumpleanosconstitucion',date('Y-m-d',strtotime($this->request->data['Cliente']['fchcumpleanosconstitucion'])));

			if (isset($this->request->data['Cliente']['inscripcionregistrocomercio']))
				if($this->request->data['Cliente']['inscripcionregistrocomercio']!="")
					$this->request->data('Cliente.inscripcionregistrocomercio',date('Y-m-d',strtotime($this->request->data['Cliente']['inscripcionregistrocomercio'])));

			if (isset($this->request->data['Cliente']['fchiniciocliente']))
				if($this->request->data['Cliente']['fchiniciocliente']!="")
					$this->request->data('Cliente.fchiniciocliente',date('Y-m-d',strtotime($this->request->data['Cliente']['fchiniciocliente'])));
		
			if($this->request->data['Cliente']['vtocaia']!="")
				$this->request->data('Cliente.vtocaia',date('Y-m-d',strtotime($this->request->data['Cliente']['vtocaia'])));
			if($this->request->data['Cliente']['vtocaib']!="")
				$this->request->data('Cliente.vtocaib',date('Y-m-d',strtotime($this->request->data['Cliente']['vtocaib'])));
			if($this->request->data['Cliente']['vtocaic']!="")
				$this->request->data('Cliente.vtocaic',date('Y-m-d',strtotime($this->request->data['Cliente']['vtocaic'])));

			$ClienteGrupoId_nuevo = $this->request->data['Cliente']['grupocliente_id'];
			$conditionsCli = array('Cliente.id' => $this->request->data['Cliente']['id']);
			$Cliente = $this->Cliente->find('first',array('conditions' =>$conditionsCli));
			$ClienteGrupoId_anterior = $Cliente['Cliente']['grupocliente_id'];

			if($this->Cliente->save($this->data))
			{				
				
				if ($ClienteGrupoId_nuevo == $ClienteGrupoId_anterior)
					echo 'El Cliente ha sido modificado.';
				else					
					echo 'redireccionar';
					//return $this->redirect(array('action' => 'view', $this->request->data['Cliente']['id']));									
			}
			else
			{ 
				echo 'El NO Cliente ha sido modificado.';
				//$this->Session->setFlash(__('El NO Cliente ha sido modificado.'));		
				//return $this->redirect(array('action' => 'index'));				
			} 
		} 
	}
	public function editajax($cliId = null,$nombre = null,$Dni= null,$Honorario=null,$Cuitcontribullente= null,$Numinscripcionconveniomultilateral= null,
		$Tipopersona = null,$Tipopersonajuridica = null,$Fchcorteejerciciofiscal= null,$Fchcumpleanosconstitucion= null,$Anosduracion= null,
		$Inscripcionregistrocomercio = null,$Modificacionescontrato = null,$Descripcionactividad= null,$Fchiniciocliente= null,$Fchfincliente= null) {

	 	$this->request->onlyAllow('ajax');
		//Configure::write('debug', 2);
		if ($this->Cliente->exists($cliId)) {
			//throw new NotFoundException(__('Evento de cliente invalido'));
			$this->Cliente->read(null, $cliId);
			$this->Cliente->set('nombre',$nombre);
			$this->Cliente->set('dni',$Dni);
			$this->Cliente->set('honorario',$Honorario);
			$this->Cliente->set('cuitcontribullente',$Cuitcontribullente);
			$this->Cliente->set('numinscripcionconveniomultilateral',$Numinscripcionconveniomultilateral);

			$this->Cliente->set('tipopersona',$Tipopersona);
			$this->Cliente->set('tipopersonajuridica',$Tipopersonajuridica);
			//$this->Cliente->set('fchcorteejerciciofiscal',date('Y-m-d',strtotime($Fchcorteejerciciofiscal)));
			$this->Cliente->set('fchcumpleanosconstitucion',date('Y-m-d',strtotime($Fchcumpleanosconstitucion)));
			$this->Cliente->set('anosduracion',$Anosduracion);

			$this->Cliente->set('inscripcionregistrocomercio',date('Y-m-d',strtotime($Inscripcionregistrocomercio)));
			$this->Cliente->set('modificacionescontrato',$Modificacionescontrato);
			$this->Cliente->set('descripcionactividad',$Descripcionactividad);
			$this->Cliente->set('fchiniciocliente',date('Y-m-d',strtotime($Fchiniciocliente)));
			$this->Cliente->set('fchfincliente',date('Y-m-d',strtotime($Fchfincliente)));

			if($this->Cliente->save()){
				$this->set('respuesta','El Cliente ha sido modificado.');	
				$this->set('evento_id',$this->Cliente->getLastInsertID());
			}else{
				$this->set('respuesta','error');	
			}
		}else{
			$this->set('respuesta','error');			
		}
		$this->layout = 'ajax';
		$this->render('editajax');
	}
	public function delete($id = null) {
		$this->Cliente->id = $id;
		if (!$this->Cliente->exists()) {
			throw new NotFoundException(__('Invalid cliente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cliente->delete()) {
			$this->Session->setFlash(__('The cliente has been deleted.'));
		} else {
			$this->Session->setFlash(__('The cliente could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
?>