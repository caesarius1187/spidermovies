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

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Conceptosrestante->recursive = 0;
		$this->set('conceptosrestantes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Conceptosrestante->exists($id)) {
			throw new NotFoundException(__('Invalid conceptosrestante'));
		}
		$options = array('conditions' => array('Conceptosrestante.' . $this->Conceptosrestante->primaryKey => $id));
		$this->set('conceptosrestante', $this->Conceptosrestante->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
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

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
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
	

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
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

}
