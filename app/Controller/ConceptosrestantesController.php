<?php
App::uses('AppController', 'Controller');
/**
 * Conceptosrestantes Controller
 *
 * @property Conceptosrestante $Conceptosrestante
 * @property PaginatorComponent $Paginator
 */
class ConceptosrestantesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function index() {
		$this->Conceptosrestante->recursive = 0;
		$this->set('conceptosrestantes', $this->Paginator->paginate());
	}
	public function cargar($id=null,$periodo=null){
		$this->loadModel('Conceptostipo');
		$this->loadModel('Localidade');
		$this->loadModel('Partido');
		$this->loadModel('Impcli');
		$this->loadModel('Cbu');
		$this->loadModel('Cliente');
		$this->loadModel('Comprobante');

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

		$optionspuntosdeventa = array(
			'conditions' =>array('Puntosdeventa.cliente_id' => $id,),
			'order'=>array()
		);
		$puntosdeventas = $this->Cliente->Puntosdeventa->find('list',$optionspuntosdeventa );
		$this->set(compact('puntosdeventas'));

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
							//'Impuesto.organismo <> "banco"'
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
		$clienteCbuOptions = array(
			'contain'=>array(
			),
			'joins'=>array(
				array('table'=>'impclis',
					'alias' => 'Impcli',
					'type'=>'inner',
					'conditions'=> array(
						'Impcli.id = Cbu.impcli_id',
						'Impcli.cliente_id'=> $id
					)
				),
			)
		);
		$cbus = $this->Cbu->find('list',$clienteCbuOptions);
		$this->set(compact('cbus'));

		$comprobantes = $this->Comprobante->find('list');

		$this->set(compact('comprobantes'));


	}

	public function view($id = null) {
		if (!$this->Conceptosrestante->exists($id)) {
			throw new NotFoundException(__('Invalid conceptosrestante'));
		}
		$options = array('conditions' => array('Conceptosrestante.' . $this->Conceptosrestante->primaryKey => $id));
		$this->set('conceptosrestante', $this->Conceptosrestante->find('first', $options));
	}

	public function addajax() {
		$this->loadModel('Partido');
		$this->loadModel('Localidade');
	 	$this->autoRender=false; 
	 	if ($this->request->is('post')) {
	 		
			$this->Conceptosrestante->create();
			if($this->request->data['Conceptosrestante']['fecha']!="")				
				$this->request->data('Conceptosrestante.fecha',date('Y-m-d',strtotime($this->request->data['Conceptosrestante']['fecha'])));
			if ($this->Conceptosrestante->save($this->request->data)) {
				//$optionsLocalidade = array('conditions'=>array('Localidade.id'=>$this->request->data['Conceptosrestante']['partido_id']));
				//$this->Localidade->recursive = -1;
				$conditionsConceptosRestantes = array(
	            	'conditions' => array('Conceptosrestante.' . $this->Conceptosrestante->primaryKey => $this->Conceptosrestante->getLastInsertID()),
	            	'contain' => array(
	            		'Impcli'=> array(
	            			'Impuesto'
	            			),
	            		'Localidade'=>array(
			   				'Partido'=>array(
			   					'fields'=>array(
				   						'Partido.nombre'
				   					)
			   					),		   				
			   				),
						'Partido'=>array(
							'fields'=>array(
								'Partido.nombre'
							)
						),
	            		'Conceptostipo',
	            		'Comprobante'
	            		)
	            	);
				$conceptoguardado = $this->Conceptosrestante->find('first', $conditionsConceptosRestantes);
				$data = array(
		            "respuesta" => "El concepto ha sido guardado.",
		            "conceptosrestante_id" => $this->Conceptosrestante->getLastInsertID(),
		            "conceptosrestante" => $conceptoguardado,
		           	//"localidade"=> $this->Localidade->find('first',$optionsLocalidade),
		            /*AFIP*/
		            "tieneMonotributo"=> $this->request->data['Conceptosrestante']['tieneMonotributo'],
		            "tieneIVA"=> $this->request->data['Conceptosrestante']['tieneIVA'],
		            "tieneIVAPercepciones"=> $this->request->data['Conceptosrestante']['tieneIVAPercepciones'],
		            "tieneImpuestoInterno"=> $this->request->data['Conceptosrestante']['tieneImpuestoInterno'],
			        /*DGR*/
		            "tieneAgenteDePercepcionIIBB"=> $this->request->data['Conceptosrestante']['tieneAgenteDePercepcionIIBB'],
			        /*DGRM*/
		            "tieneAgenteDePercepcionActividadesVarias"=> $this->request->data['Conceptosrestante']['tieneAgenteDePercepcionActividadesVarias'],
		        );
			}
			else{
				$data = array(
		        	"respuesta" => "El concepto NO ha sido creado.Intentar de nuevo mas tarde".$myfecha,
		            "conceptosrestante_id" => 0,
		        );
			}
			$this->layout = 'ajax';
	        $this->set('data', $data);
			$this->render('serializejson');
			
			//}
		}
	}

	public function edit($id = null) {
        $this->loadModel('Partido');
        $this->loadModel('Localidade');
		$this->loadModel('Cliente');
		$this->loadModel('Impcli');
		$this->loadModel('Comprobante');
		$this->loadModel('Conceptostipo');
		$this->loadModel('Categoriamonotributo');
		if (!$this->Conceptosrestante->exists($id)) {
			throw new NotFoundException(__('Invalid conceptosrestante'));
		}
		$mostrarForm=true;
		if(!empty($this->data)){ 
			$id = $this->request->data['Conceptosrestante']['id'];
			$this->request->data['Conceptosrestante']['fecha'] = $this->request->data['Conceptosrestante']['conceptofecha'.$id];
			$this->request->data('Conceptosrestante.fecha',date('Y-m-d',strtotime($this->request->data['Conceptosrestante']['fecha'])));
			if ($this->Conceptosrestante->save($this->request->data)) {
				$respuesta='El concepto a sido guardado con exito. Pero no se puede actualizar la Tabla por favor recargue la pagina para ver los cambios';
			} else {
				$respuesta='El concepto NO a sido guardado con exito.Por intente nuevamente mas tarde';
			}
			$data = array();
            $data['respuesta']=$respuesta;
			$options = array(
				'conditions' => array('Conceptosrestante.' . $this->Conceptosrestante->primaryKey => $id),
				'contain'=>array(
					'Impcli'=>array(
				   				'Impuesto'=>array(
										'fields'=>array(
					   						'Impuesto.nombre'
					   					)
				   					)
				   				),
		   			'Localidade'=>array(
		   				'Partido'=>array(
		   					'fields'=>array(
			   						'Partido.nombre'
			   					)
		   					),		   				
		   				),
                    'Partido'=>array(
                        'fields'=>array(
                            'Partido.nombre'
                        )
                    ),
		   			'Conceptostipo'=>array(
		   				'fields'=>array(
			   						'Conceptostipo.nombre'
			   					)
		   				),	
	   				'Comprobante'=>array(
		   				'fields'=>array(
			   						'Comprobante.nombre'
			   					)
		   				),			   			
				)
			);
			$this->request->data = $this->Conceptosrestante->find('first', $options);
			$mostrarForm = false;

            $data['conceptosrestante'] = $this->Conceptosrestante->find('first', $options);

            $this->layout = 'ajax';
            $this->set('data', $data);
            $this->render('serializejson');
			return;
		} else {
			$options = array('conditions' => array('Conceptosrestante.' . $this->Conceptosrestante->primaryKey => $id));
			$this->request->data = $this->Conceptosrestante->find('first', $options);
		}
        $options = array(
            'conditions' => array('Conceptosrestante.' . $this->Conceptosrestante->primaryKey => $id),
        );
        $this->request->data = $this->Conceptosrestante->find('first', $options);

		$conditionsLocalidades = array(
			'contain'=>'Partido',
			'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
			'order'=>array('Partido.nombre','Localidade.nombre')
			);
        $localidades = $this->Localidade->find('list',$conditionsLocalidades);
        $partidos = $this->Partido->find('list');
		$comprobantes = $this->Comprobante->find('list');
		$conceptostipos = $this->Conceptostipo->find('list');
        //Debugger::dump($this->request->data);

        if( $this->request->data['Impcli']['impuesto_id']==19/*IVA*/){
            $conceptostipos[1]='Saldo de Libre Disponibilidad';
            //Debugger::dump($conceptostipos);
        }
		$this->set(compact('partidos', 'localidades', 'comprobantes', 'conceptostipos'));

		$pemes = substr($this->request->data['Conceptosrestante']['periodo'], 0, 2);
		$peanio = substr($this->request->data['Conceptosrestante']['periodo'], 3);
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
					'contain'=>array(
							'Impuesto'
						),
					'conditions' => array(
							'Impcli.cliente_id'=> $this->request->data['Conceptosrestante']['cliente_id']
						), 
					'fields'=>array('Impcli.id','Impuesto.nombre'),
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
		$impclis=$this->Impcli->find('list',$clienteImpuestosOptions);
		$this->set('impclis', $impclis);
		$clienteImpuestosOptions = array(
					'contain'=>array(
							'Impuesto'
						),
					'conditions' => array(
							'Impcli.cliente_id'=> $this->request->data['Conceptosrestante']['cliente_id']
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
						)
					);
		$impclisid=$this->Impcli->find('list',$clienteImpuestosOptions);
		$this->set('impclisid', $impclisid);

		$this->set('mostrarForm',$mostrarForm);	

		$this->set('conid',$id);
		$this->layout = 'ajax';
	}

	public function delete($id = null) {
		$this->Conceptosrestante->id = $id;
		if (!$this->Conceptosrestante->exists()) {
			throw new NotFoundException(__('Invalid Conceptosrestante'));
		}
		$this->request->onlyAllow('post');
		$data = array();
		if ($this->Conceptosrestante->delete()) {
			$data['respuesta'] = 'Pago a cuenta eliminado con exito.';
			$data['error'] = 0;
		} else {
			$data['respuesta'] = 'Pago a cuenta NO eliminado. Por favor intente mas tarde.';
			$data['error'] = 1;

		}
		$this->layout = 'ajax';
		$this->set('data', $data);
		$this->render('serializejson');

	}
	public function exportartxtretencionessifere($cliid,$periodo,$impcli){
		$this->loadModel('Cliente');
		$optionsConceptosrestantes=[
			'contain'=>[
				'Partido',
				'Comprobante'
			],
			'conditions'=>[
				'Conceptosrestante.cliente_id'=>$cliid,
				'Conceptosrestante.periodo'=>$periodo,
				'Conceptosrestante.impcli_id'=>$impcli,
				'Conceptosrestante.conceptostipo_id'=>2
			],

		];

		$conceptosrestantes= $this->Conceptosrestante->find('all',$optionsConceptosrestantes);
		$optionCliente=[
			'contain'=>[],
			'condition'=>['Cliente.id'=>$cliid]
		];
		$cliente = $this->Cliente->find('first',$optionCliente);
		$this->set(compact('conceptosrestantes','cliente','cliid','periodo'));
	}
	public function importarretencionesbancariasconveniomultilateral($cliid=null,$periodo=null){
		set_time_limit (360);
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		$this->loadModel('Partido');
		$this->loadModel('Comprobante');
		$this->loadModel('Cliente');

		$folderConceptosrestantes = WWW_ROOT.'files'.DS.'retenciones'.DS.$cliid.DS.$periodo.DS.'retenciones';
		if ($this->request->is('post')) {
			$folderConceptosrestantes = WWW_ROOT.'files'.DS.'ventas'.DS.$this->request->data['Conceptosrestante']['cliid'].DS.$this->request->data['Conceptosrestante']['periodo'].DS.'retenciones';
			$fileNameConceptosrestante = null;
			$tmpNameConceptosrestante= $this->request->data['Conceptosrestante']['archivoretenciones']['tmp_name'];
			if (!empty($tmpNameConceptosrestante)&& is_uploaded_file($tmpNameConceptosrestante)) {
				// Strip path information
				$fileNameConceptosrestante = $this->request->data['Conceptosrestante']['archivoretenciones']['name'];
				move_uploaded_file($tmpNameConceptosrestante, $folderConceptosrestantes.DS.$fileNameConceptosrestante);
				//chmod($folderVentas.DS.$fileNameVenta, 0777);
			}
		}
	
		//Partidos
		$partidos = $this->Partido->find('list');
		//Comprobantes
		$comprobantes = $this->Comprobante->find('list',['fields'=>['id','codnamedos']]);
		$this->set(compact('comprobantes', 'partidos'));

		$this->set(compact('cliid','periodo','folderConceptosrestantes'));

		//Aca vamos a informar el estado de los impeustos que necesitamos (por ahora solo necesitamos Monotributo) para importar las ventas
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
		$cliente=$this->Cliente->find('first', array(
				'contain'=>array(
					'Impcli'=>array(
						'Periodosactivo'=>array(
							'conditions'=>$conditionsImpCliHabilitados
						)
					)
				),
				'conditions' => array(
					'id' => $cliid,
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

		$optionsconceptosrestantesperiodo=array(
			'contain'=>array(
			),
			'fields'=>array(
			),
			'conditions'=>array(
				'Conceptosrestante.conceptostipo_id'=>2,
				'Conceptosrestante.periodo'=>$periodo,
				'Conceptosrestante.cliente_id'=>$cliid
			)
		);
		$conceptosrestantesperiodo = $this->Conceptosrestante->find('all',$optionsconceptosrestantesperiodo);
		$this->set('conceptosrestantesperiodo',$conceptosrestantesperiodo);
	}
	public function cargarventas(){
		$data=array();
		if ($this->request->is('post')) {
			$params = array();
			$myParser = new ParserUnlimited();
			$myParser->my_parse_str($this->request->data['Venta'][0]['jsonencript'],$params);
			foreach ($params['data']['Venta'] as $k => $miventa){
				$mifecha = $miventa['fecha'];
				$params['data']['Venta'][$k]['fecha'] = date('Y-m-d',strtotime($mifecha));
			}
			$this->Venta->create();
			if ($this->Venta->saveAll($params['data']['Venta'])) {
				//$data['params'] = $params;
				//if (1==1) {
				$data['respuesta'] = 'Las Ventas han sido guardadas.';
			} else {
				$data['respuesta'] = 'Error al guardar ventas, por favor intende de nuevo mas tarde.';
			}
		}else{
			$data['respuesta'] = 'acceso denegado';
		}
		$this->layout = 'ajax';
		$this->set('data', $data);
		$this->render('serializejson');
		/*return $this->redirect(
			array(
				'controller'=>'clientes',
				'action' => 'tareacargar',
				$this->request->data['Venta'][0]['cliente_id'],
				$this->request->data['Venta'][0]['periodo']));*/
	}
	public function deletefile($name=null,$cliid=null,$folder=null,$periodo=null){
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		$file = WWW_ROOT.'files'.DS.'ventas'.DS.$cliid.DS.$periodo.DS.$folder.DS.$name;
		chmod($file, 0777);
		if( is_file( $file ) AND is_readable( $file ) ){
			if(unlink($file)){
				$this->Session->setFlash(__('El Archivo ha sido eliminado.File:'.$file.fileperms ($file)));
			}else{
				$this->Session->setFlash(__('El Archivo NO ha sido eliminado.Por favor nuevamente intente mas tarde.'.$file.fileperms ($file)));
			}
		}else{
			$this->Session->setFlash(__('No se puede acceder al archivo'.$file));
		}
		return $this->redirect(
			array(
				'controller'=>'ventas',
				'action' => 'importar',
				$cliid,
				$periodo
			)
		);
	}
}
