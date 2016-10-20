<?php
App::uses('AppController', 'Controller');
/**
 * Impclis Controller
 *
 * @property Impcli $Impcli
 * @property PaginatorComponent $Paginator
 */
class ImpclisController extends AppController {

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
		$this->Impcli->recursive = 0;
		$this->set('impclis', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Impcli->exists($id)) {
			throw new NotFoundException(__('Invalid impcli'));
		}
		$options = array('conditions' => array('Impcli.' . $this->Impcli->primaryKey => $id));
		$this->set('impcli', $this->Impcli->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$resp ="";
		$this->loadModel('Periodosactivo');
		if ($this->request->is('post')) {
			$this->Impcli->create();
			//tenemos que revisar si ya esta creado el impcli del impuesto seleccionado
			//vamos a buscar un impcli con el impcli_id y el cliente_id que nos viene en $this->request->data
			$id = 0;
			$options = array(
					'contain'=>array(
						'Periodosactivo'=>array(
												'conditions' => array(
								                'Periodosactivo.hasta' => null, 
								            ),
						        	 	), 
						'Impuesto'						
						),
					'conditions' => array(
						'Impcli.impuesto_id'=> $this->request->data['Impcli']['impuesto_id'],
						'Impcli.cliente_id'=> $this->request->data['Impcli']['cliente_id'],
						)
					);
			$createdImp = $this->Impcli->find('first', $options);
			$impcliCreado= false;
			
			if(count($createdImp)>0){
				//el impcli ya esta creado por lo que ahora resta buscar los periodos activos y ver si se puede crear uno
				$impcliCreado= true;
				$this->set('impcliCreado','Error1: El impuesto ya esta relacionado, se cargo el periodo activo.');	

				$id = $createdImp['Impcli']['id'];
			}else{
				//el impcli no existe y lo creamos por aqui
				if ($this->Impcli->save($this->request->data)) {
					$id = $this->Impcli->getLastInsertID();
					$options = array(
						'contain'=>array(
							'Periodosactivo'=>array(
												'conditions' => array(
								                'Periodosactivo.hasta' => null, 
								            ),
						        	 	), 
							'Impuesto'
						),
						'conditions' => array(
							'Impcli.' . $this->Impcli->primaryKey => $id
							)
						);
					$createdImp = $this->Impcli->find('first', $options);									
				}
				else{
					$this->set('respuesta','Error: NO se relaciono impuesto para cliente. Intente de nuevo.');	
					$this->autoRender=false; 
					$this->layout = 'ajax';
					$this->render('add');
					return;
				}
			}		
			//si pasa esos dos controles agregamos la fecha de alta como un nuevo periodoactivo
			$periodoAbierto= false;
			$altaContenidaEnPeriodo= false;
			foreach ($createdImp['Periodosactivo'] as $periodoactivo) {

				//tenemos que buscar los periodos activos.. si tiene uno sin cerrar no agregamos nada
			    if(is_null($periodoactivo['hasta']) || empty($periodoactivo['hasta'])){
			    	$periodoAbierto= true;
			    }
			    //hay 3 campos que pueden tener el valor de periodo alta: alta, altadgr, altadgrm
			    //cualquiera de los 3 que sea != null se guarda en alta
			    
				//si tiene todos cerrados tenemos que ver que los periodos no contengan a la fecha de alta
			    if($periodoactivo['desde'] <=  $this->request->data['Impcli']['alta']
			    	&&
			    	$periodoactivo['hasta'] >=  $this->request->data['Impcli']['alta']){
			    	$altaContenidaEnPeriodo= true;
			    }
			}

			if(!$periodoAbierto && !$altaContenidaEnPeriodo){
				$this->Periodosactivo->create();
				$this->Periodosactivo->set('impcli_id',$id);
				$this->Periodosactivo->set('desde',$this->request->data['Impcli']['alta']);
				if ($this->Periodosactivo->save()) {
					$this->set('Periodoalta',$this->request->data['Impcli']['alta']);
				}else{
					$this->set('respuesta','Error: NO se pudo dar de alta el impuesto(440). Intentelo de nuevo mas tarde.');	
					$this->autoRender=false; 
					$this->layout = 'ajax';
					$this->render('add');
					return;
				}
			}else{
				if($periodoAbierto){
					$this->set('respuesta','Error: NO se pudo dar de alta el impuesto(ya existe un periodo activo abierto para este impuesto). Intentelo de nuevo mas tarde.');	
				}else if($altaContenidaEnPeriodo){
					$this->set('respuesta','Error: NO se pudo dar de alta el impuesto(el periodo de alta ya esta contenido en otro periodo activo). Intentelo de nuevo mas tarde.');	
				}else{
					$this->set('respuesta','Error: NO se pudo dar de alta el impuesto(441). Intentelo de nuevo mas tarde.');	
				}
				$this->autoRender=false; 
				$this->layout = 'ajax';
				$this->render('add');
				return;
			}
			$this->set('impcli',$createdImp);
			$this->autoRender=false; 		
		}
		$this->layout = 'ajax';
		$this->render('add');
	}
	public function addbancosindicato() {
		if ($this->request->is('post')) {
			$this->request->data['Impcli']['desde'] = $this->request->data['Impcli']['mesdesde'].'-'.$this->request->data['Impcli']['aniodesde'];
			$this->request->data['Impcli']['hasta'] = $this->request->data['Impcli']['meshasta'].'-'.$this->request->data['Impcli']['aniohasta'];
			$this->Impcli->create();
			if ($this->Impcli->save($this->request->data)) {
				$this->Session->setFlash(__('Se relaciono  con exito.'));
				return $this->redirect(array('controller'=>'Clientes','action' => 'index', $this->request->data['cliente_id']));
			} else {
				$this->Session->setFlash(__('NO se relaciono  con exito.Por favor intentelo mas tarde'));
			}
		}
		$clientes = $this->Impcli->Cliente->find('list');
		$impuestos = $this->Impcli->Impuesto->find('list');
		$this->set(compact('clientes', 'impuestos'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Impcli->exists($id)) {
			throw new NotFoundException(__('Invalid impcli'));
		}
		if ($this->request->is('post')) {
			
			if ($this->Impcli->save($this->request->data)) {
				//dont answer anithing bc theres just ajax call to save
				$this->set('showTheForm',false);
				$this->layout = 'ajax';
				if(!empty($this->data)){ 
					echo 'Impuesto Modificado'; 
				}else{ 
					echo 'Impuesto No Modificado'; 
				} 
				$options = array('conditions' => array('Impcli.' . $this->Impcli->primaryKey => $id));
				$this->request->data = $this->Impcli->find('first', $options);
				return ;
			} else {

			}
		} else {
			
		}
		$options = array('conditions' => array('Impcli.' . $this->Impcli->primaryKey => $id));
		$this->request->data = $this->Impcli->find('first', $options);
		$clientes = $this->Impcli->Cliente->find('list');
		$impuestos = $this->Impcli->Impuesto->find('list');
		$this->set(compact('clientes', 'impuestos'));
	}
	public function editajax($id=null,$cliid = null) {

	 	//$this->request->onlyAllow('ajax');
		$this->loadModel('Clientes');
		if (!$this->Impcli->exists($id)) {
			throw new NotFoundException(__('Impuesto de Cliente Invalido'));
		}
		
		$options = array('conditions' => array('Impcli.' . $this->Impcli->primaryKey => $id));
		$this->request->data = $this->Impcli->find('first', $options);

		$optionsCli = array('conditions' => array('Cliente.id' => $cliid));
		$clientes = $this->Impcli->Cliente->find('list', $optionsCli);

		$optionsImp = array('conditions' => array('Impuesto.id' => $this->request->data['Impcli']['impuesto_id']));
		$impuestos = $this->Impcli->Impuesto->find('list', $optionsImp);
		
		$this->set(compact('clientes', 'impuestos'));
		$this->set('showTheForm',$this->request->is('post'));

		$this->layout = 'ajax';
		$categoriasmonotributos = array('B'=>'B','C'=>'C','D'=>'D','E'=>'E','F'=>'F','G'=>'G','H'=>'H','I'=>'I','J'=>'J','K'=>'K','L'=>'L');
		$this->set(compact('categoriasmonotributos'));
		$this->render('edit');	
	}
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Impcli->id = $id;
		$this->loadModel('Periodosactivo');
		$data = array();
		if (!$this->Impcli->exists()) {
			$data['respuesta']='El impuesto del cliente NO existe.';
		}
		$options = array(
			'conditions' => array('Impcli.' . $this->Impcli->primaryKey => $id),
			'contain' => array('Eventosimpuesto')
			);
		$impcli = $this->Impcli->find('first', $options);

		if(count($impcli['Eventosimpuesto'])){
			$data['respuesta']='El impuesto del cliente NO ha sido eliminado. Se han creado registros sobre pagos en diferentes periodos.';
			$this->set('data',$data);
			$this->layout = 'ajax';
			$this->render('serializejson');
			return;
		}


		$this->request->onlyAllow('post', 'delete');
		if ($this->Impcli->delete()) {
			$data['respuesta']='El impuesto del cliente ha sido eliminado.';
			$this->Periodosactivo->deleteAll(array('Periodosactivo.impcli_id' => $id), false);

		} else {
			$data['respuesta']='El impuesto del cliente NO ha sido eliminado. Por favor intente de nuevo';
		}

		$this->set('data',$data);
		$this->layout = 'ajax';
		$this->render('serializejson');
	}

	public function papeldetrabajoconveniomultilateral($impcliid=null,$periodo=null) {
		$this->loadModel('Actividadcliente');
		$this->loadModel('Conceptosrestante');
		$this->set('periodo',$periodo);
		$this->set('impcliid',$impcliid);

		$options = array(
			'contain'=>array(
				'Cliente',
				'Impcliprovincia'=>array(
					'Partido',
					'conditions'=>array(
							"Impcliprovincia.ano = SUBSTRING('".$periodo."',4,7)"
						)
					)
				),
			'conditions' => array('Impcli.' . $this->Impcli->primaryKey => $impcliid)
        );
		$impcli = $this->Impcli->find('first',$options);
		for ($i=0;$i<count($impcli['Impcliprovincia'])-1;$i++){
			for ($j=$i;$j<count($impcli['Impcliprovincia']);$j++) {
				$burbuja = $impcli['Impcliprovincia'][$i]['Partido']['codigo'];
				$aux = $impcli['Impcliprovincia'][$j]['Partido']['codigo'];
				if($burbuja>$aux){
					$myaux=$impcli['Impcliprovincia'][$i];
					$impcli['Impcliprovincia'][$i]=$impcli['Impcliprovincia'][$j];
					$impcli['Impcliprovincia'][$j]=$myaux;
				}
			}
		}
		$this->set('impcli',$impcli);
        $pemes = substr($periodo, 0, 2);
        $peanio = substr($periodo, 3);
        $esMenorQueHasta = array(
            //HASTA es mayor que el periodo
            'OR'=>array(
                'SUBSTRING(Alicuota.hasta,4,7)*1 > '.$peanio.'*1',
                'AND'=>array(
                    'SUBSTRING(Alicuota.hasta,4,7)*1 >= '.$peanio.'*1',
                    'SUBSTRING(Alicuota.hasta,1,2) >= '.$pemes.'*1'
                ),
            )
        );
        //B: Es mayor que periodo Desde
        $esMayorQueDesde = array(
            'OR'=>array(
                'SUBSTRING(Alicuota.desde,4,7)*1 < '.$peanio.'*1',
                'AND'=>array(
                    'SUBSTRING(Alicuota.desde,4,7)*1 <= '.$peanio.'*1',
                    'SUBSTRING(Alicuota.desde,1,2) <= '.$pemes.'*1'
                ),
            )
        );
        $periodoNull = array(
            'OR'=>array(
                array('Alicuota.hasta'=>null),
                array('Alicuota.hasta'=>""),
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
		//vamos a buscar las actividades y las vamos a traer con las ventas
		$conditionsActividades = array(
			'contain'=>array(
				'Actividade'=>array(
					'Alicuota'=>array(
						),
					),
				'Encuadrealicuota'=>array(
					),
				'Venta'=>array(
					'Localidade'=>array(
						'Partido'
						),
					'Comprobante',
					'conditions'=>array(
						'Venta.periodo'=>$periodo
						)
					),
				'Compra'=>array(
						'Localidade'=>array(
								'Partido'
							),
						'conditions'=>array(
							'Compra.periodo'=>$periodo
							)
						),
			),
			'conditions' => array(
				'Actividadcliente.cliente_id'=>$impcli['Cliente']['id']
				), 
		);
		$actividadclientes = $this->Actividadcliente->find('all',$conditionsActividades);
		$this->set('actividadclientes',$actividadclientes);
		/*aca le vamos a sacar un mes al periodo para que los conceptos restantes que traigamos sean del periodo anterior*/
		$conditionsConceptosrestantes=array(
			'contain'=>array(
				'Localidade',
				'Partido',
			),
			'conditions'=>array(
				'Conceptosrestante.periodo'=>$periodo,
				'Conceptosrestante.impcli_id'=>$impcliid,
			)
		);
		$conceptosrestantes = $this->Conceptosrestante->find('all',$conditionsConceptosrestantes);
		$this->set('conceptosrestantes',$conceptosrestantes);

		/*Datos Para el DIV de control de carga de impcliprovincias previas*/
		//aca vamos a controlar que las provincias que esten activadas en este impuesto sean las mismas que las provincias donde se han cargado ventas y compras
		$provinciasActivadas = array();
		$provinciasVentas = array();
		$provinciasVentasDiff = array();
		$provinciasCompras = array();
		$provinciasComprasDiff = array();
		foreach ($impcli['Impcliprovincia'] as $key => $impcliprovincia) {
			if(!array_key_exists($impcliprovincia['partido_id'], $provinciasActivadas)){
		        $provinciasActivadas[$impcliprovincia['partido_id']]=$impcliprovincia['Partido']['nombre'];
		    }
		}
		foreach ($actividadclientes as $key => $actividadcliente) {
			foreach ($actividadcliente['Venta'] as $key => $venta) {
				if(!array_key_exists($venta['Localidade']['partido_id'], $provinciasVentas)){
					 $provinciasVentas[$venta['Localidade']['partido_id']]=$venta['Localidade']['Partido']['nombre'];
			    }
			}
			foreach ($actividadcliente['Compra'] as $key => $compra) {
				if(!array_key_exists($compra['Localidade']['partido_id'], $provinciasCompras)){
					 $provinciasCompras[$compra['Localidade']['partido_id']]=$compra['Localidade']['Partido']['nombre'];
			    }
			}
		}
		// ya tenemos los array donde estan las provincias activadas, las de las compras y las de las ventas hay que compararlas y generar alertas para que
		// el informe controle y no te deje avanzar hasta que el array de compras y de ventas este vacio
		$provinciasVentasDiff = array_diff($provinciasVentas,$provinciasActivadas);
		$provinciasComprasDiff = array_diff($provinciasCompras,$provinciasActivadas);
		$this->set(compact('provinciasActivadas','provinciasVentas','provinciasCompras','provinciasVentasDiff','provinciasComprasDiff'));
	}
	public function papeldetrabajoactividadesvarias($impcliid=null,$periodo=null) {
		$this->loadModel('Actividadcliente');
		$this->set('periodo',$periodo);
		$this->set('impcliid',$impcliid);

		$options = array(
			'contain'=>array(
				'Cliente',
				'Impcliprovincia'=>array(
					'Localidade'=>array(
						'Partido'
						),
					'conditions'=>array(
							"Impcliprovincia.ano = SUBSTRING('".$periodo."',4,7)"
						)
					)
				),
			'conditions' => array('Impcli.' . $this->Impcli->primaryKey => $impcliid));
		$impcli = $this->Impcli->find('first', $options);
		$this->set('impcli',$impcli);

		//vamos a buscar las actividades y las vamos a traer con las ventas
		$conditionsActividades = array(
			'contain'=>array(
				'Actividade'=>array(
					'Alicuota'=>array(
						),	
					),
				'Basesprorrateada'=>array(
					'Impcliprovincia'=>array(
						'Partido'
						),
					'conditions'=>array(
						'Basesprorrateada.periodo'=>$periodo
						)
					),
				'Encuadrealicuota'=>array(//esto se puede mejorar trayendo solo los encuadresalicuotas que sean de las localidades que estan en impcliprovincia
					),
				'Venta'=>array(
					'Localidade'=>array(
						'Partido'
						),
					'Comprobante',
					'conditions'=>array(
						'Venta.periodo'=>$periodo
						)
					),
				'Compra'=>array(
					'Localidade'=>array(
						'Partido'
						),
					'conditions'=>array(
						'Compra.periodo'=>$periodo
						)
					),
				'Cliente'=>array(					
					'Impcli'=>array(
						'Conceptosrestante'=>array(
							'conditions'=>array(
								'Conceptosrestante.periodo'=>$periodo,
								)	
							),
						'conditions'=>array(
							'Impcli.impuesto_id'=>6/*Actividades Varias*/,
							)
					),						
					'fields'=>array('Cliente.id','Cliente.nombre','Cliente.cuitcontribullente'),
				),
			),
			'conditions' => array(
				'Actividadcliente.cliente_id'=>$impcli['Cliente']['id']
				), 
		);
		$actividadclientes = $this->Actividadcliente->find('all',$conditionsActividades);
		$this->set('actividadclientes',$actividadclientes);
		//aca vamos a controlar que las provincias que esten activadas en este impuesto sean las mismas que las provincias donde se han cargado ventas y compras
		$provinciasActivadas = array();
		$provinciasVentas = array();
		$provinciasVentasDiff = array();
		$provinciasCompras = array();
		$provinciasComprasDiff = array();
		foreach ($impcli['Impcliprovincia'] as $key => $impcliprovincia) {
			if(!array_key_exists($impcliprovincia['localidade_id'], $provinciasActivadas)){
		        $provinciasActivadas[$impcliprovincia['localidade_id']]=$impcliprovincia['Localidade']['Partido']['nombre']."-".$impcliprovincia['Localidade']['nombre'];
		    }
		}
		foreach ($actividadclientes as $key => $actividadcliente) {
			foreach ($actividadcliente['Venta'] as $key => $venta) {
				if(!array_key_exists($venta['Localidade']['id'], $provinciasVentas)){
				 	$provinciasVentas[$venta['Localidade']['id']]=$venta['Localidade']['Partido']['nombre']."-".$venta['Localidade']['nombre'];
			    }
			}
			foreach ($actividadcliente['Compra'] as $key => $compra) {
				if(!array_key_exists($compra['Localidade']['id'], $provinciasCompras)){
				 	$provinciasCompras[$compra['Localidade']['id']]=
                        $compra['Localidade']['Partido']['nombre']."-".
						$compra['Localidade']['nombre'];
			    }
			}
		}
		// ya tenemos los array donde estan las provincias activadas, las de las compras y las de las ventas hay que compararlas y generar alertas para que
		// el informe controle y no te deje avanzar hasta que el array de compras y de ventas este vacio
		$provinciasVentasDiff = array_diff($provinciasVentas,$provinciasActivadas);
		$provinciasComprasDiff = array_diff($provinciasCompras,$provinciasActivadas);
		$this->set(compact('provinciasActivadas','provinciasVentas','provinciasCompras','provinciasVentasDiff','provinciasComprasDiff'));
	}
	public function papeldetrabajomonotributo($impcliid=null,$periodo=null){
		$this->loadModel('Venta');
		$this->loadModel('Compra');
		$this->loadModel('Domicilio');
		$this->loadModel('Categoriamonotributo');
		$this->loadModel('Actividadcliente');
        $this->loadModel('Puntosdeventa');
        $this->loadModel('Comprobante');

		$strDatePeriodo='01-'.$periodo;
		$mesPeriodo = date('m',strtotime($strDatePeriodo));
		$añoPeriodo = date('Y',strtotime($strDatePeriodo));
		$date = strtotime('2012-05-01 -4 months');
		//primero hay que ver en que cuatrimestre estamos ese va a ser nuestro 3 cuatrimestre, tengo que calcular los 2 anteriores y determinar cual es el periodo en el que va a comenzar el año
		$mesinicioDelAño = '01';
		$añoinicioDelAño = $añoPeriodo;
        $mesParaProximaRecategorizacion= 1;
        $mesinicioDelCuatrimestre= '01';
		$showBtnChangeRecategorizacion = false;
		switch ($mesPeriodo) {
			case '01':
				$mesParaProximaRecategorizacion= 4;
                $mesinicioDelCuatrimestre = '01';
				$mesinicioDelAño= '05';
				$añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
				break;
			case '02':
				$mesParaProximaRecategorizacion= 3;
                $mesinicioDelCuatrimestre = '01';
                $mesinicioDelAño= '05';
				$añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
				break;
			case '03':
				$mesParaProximaRecategorizacion= 2;
                $mesinicioDelCuatrimestre = '01';
                $mesinicioDelAño= '05';
				$añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
				break;
			case '04':
				$mesParaProximaRecategorizacion= 1;
                $mesinicioDelCuatrimestre = '01';
                $mesinicioDelAño= '05';
				$añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
				$showBtnChangeRecategorizacion = true;
				break;
			case '05':
				$mesParaProximaRecategorizacion= 4;
                $mesinicioDelCuatrimestre = '05';
                $mesinicioDelAño= '09';
				$añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
				break;
			case '06':
				$mesParaProximaRecategorizacion= 3;
                $mesinicioDelCuatrimestre = '05';
                $mesinicioDelAño= '09';
				$añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
				break;
			case '07':
				$mesParaProximaRecategorizacion= 2;
                $mesinicioDelCuatrimestre = '05';
                $mesinicioDelAño= '09';
				$añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
				break;
			case '08':
				$mesParaProximaRecategorizacion= 1;
                $mesinicioDelCuatrimestre = '05';
                $mesinicioDelAño= '09';
				$añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
				$showBtnChangeRecategorizacion = true;
				break;
			case '09':
				$mesParaProximaRecategorizacion= 4;
                $mesinicioDelCuatrimestre = '09';
                $mesinicioDelAño= '01';
				break;
			case '10':
				$mesParaProximaRecategorizacion= 3;
                $mesinicioDelCuatrimestre = '09';
                $mesinicioDelAño= '01';
				break;
			case '11':
				$mesParaProximaRecategorizacion= 2;
                $mesinicioDelCuatrimestre = '09';
                $mesinicioDelAño= '01';
				break;
			case '12':
				$mesParaProximaRecategorizacion= 1;
                $mesinicioDelCuatrimestre = '09';
				$mesinicioDelAño= '01';
				$showBtnChangeRecategorizacion = true;
				break;
			default:
				# code...
				break;
		}
		$periodoDeInicio = '01-'.$mesinicioDelAño.'-'.$añoinicioDelAño;
		$mesFinDelAño="";
		$añoFINDelAño="";
		$mesFinDelAño = date('m',strtotime($periodoDeInicio.' +12 months'));
		$añoFINDelAño = date('Y',strtotime($periodoDeInicio.' +12 months'));
		$periodoDeFIN = date('d-m-Y',strtotime($periodoDeInicio.' +12 months'));
		$this->set(compact('periodoDeInicio','periodoDeFIN','mesParaProximaRecategorizacion','showBtnChangeRecategorizacion'));

		$options = array('conditions' => array('Impcli.' . $this->Impcli->primaryKey => $impcliid));
		$myImpcli = $this->Impcli->find('first', $options);
		$this->set('impcli',$myImpcli);
		$esMayorQuePeriodoInicio = array(
				//Periodo FIN es mayor que el periodo
            	'OR'=>array(
            		'SUBSTRING(Venta.periodo,4,7)*1 > '.$añoinicioDelAño.'*1',
            		'AND'=>array(
            			'SUBSTRING(Venta.periodo,4,7)*1 >= '.$añoinicioDelAño.'*1',
            			'SUBSTRING(Venta.periodo,1,2) >= '.$mesinicioDelAño.'*1'
            			),												            		
            		)
            	);
		//B: Es mayor que periodo Desde
		$esMenorQuePeriodoFIN = array(
			'OR'=>array(
	    		'SUBSTRING(Venta.periodo,4,7)*1 < '.$añoFINDelAño.'*1',
	    		'AND'=>array(
	    			'SUBSTRING(Venta.periodo,4,7)*1 <= '.$añoFINDelAño.'*1',
	    			'SUBSTRING(Venta.periodo,1,2) <= '.$mesFinDelAño.'*1'
	    			),												            		
	    		)
		);
		$esMenorQuePeriodoFINConsulta = array(
			'OR'=>array(
	    		'SUBSTRING(Venta.periodo,4,7)*1 < '.$añoPeriodo.'*1',
	    		'AND'=>array(
	    			'SUBSTRING(Venta.periodo,4,7)*1 <= '.$añoPeriodo.'*1',
	    			'SUBSTRING(Venta.periodo,1,2) <= '.$mesPeriodo.'*1'
	    			),												            		
	    		)
		);
		$esMayorQuePeriodoInicioCompra = array(
				//Periodo FIN es mayor que el periodo
            	'OR'=>array(
            		'SUBSTRING(Compra.periodo,4,7)*1 > '.$añoinicioDelAño.'*1',
            		'AND'=>array(
            			'SUBSTRING(Compra.periodo,4,7)*1 >= '.$añoinicioDelAño.'*1',
            			'SUBSTRING(Compra.periodo,1,2) >= '.$mesinicioDelAño.'*1'
            			),												            		
            		)
            	);
		//B: Es mayor que periodo Desde
		$esMenorQuePeriodoFINCompra = array(
			'OR'=>array(
	    		'SUBSTRING(Compra.periodo,4,7)*1 < '.$añoFINDelAño.'*1',
	    		'AND'=>array(
	    			'SUBSTRING(Compra.periodo,4,7)*1 <= '.$añoFINDelAño.'*1',
	    			'SUBSTRING(Compra.periodo,1,2) <= '.$mesFinDelAño.'*1'
	    			),												            		
	    		)
		);
		$esMenorQuePeriodoFINCompraConsulta = array(
			'OR'=>array(
	    		'SUBSTRING(Compra.periodo,4,7)*1 < '.$añoinicioDelAño.'*1',
	    		'AND'=>array(
	    			'SUBSTRING(Compra.periodo,4,7)*1 <= '.$añoinicioDelAño.'*1',
	    			'SUBSTRING(Compra.periodo,1,2) <= '.$mesinicioDelAño.'*1'
	    			),												            		
	    		)
		);
		$ventas = $this->Venta->find('all',array(
									'fields' => array('SUM(Venta.total) AS total','Venta.periodo','Venta.comprobante_id','Comprobante.tipodebitoasociado','Venta.actividadcliente_id'),
									'contain'=>array(
										'Comprobante',
										'Actividadcliente',	
										),								
									'conditions'=>array(
										'Venta.cliente_id'=> $myImpcli['Impcli']['cliente_id'] ,
								 		'AND'=>array(
									 				$esMayorQuePeriodoInicio,
									 				$esMenorQuePeriodoFIN,
									 				$esMenorQuePeriodoFINConsulta
									 			)
									 		),
								 	'group'=>array(
									 	'Venta.periodo','Venta.comprobante_id','Venta.actividadcliente_id'
									 	)
							 		));
		$optionActividad=array(
			'contain' => array('Actividade'),
			'conditions'=>array(
					"Actividadcliente.cliente_id" => $myImpcli['Impcli']['cliente_id'],
				),
		);
		$actividadclientes = $this->Actividadcliente->find('all',$optionActividad);
		$compras = $this->Compra->find('all',array(
									'fields' => array('SUM(Compra.total) AS total','SUM(Compra.kw) AS kw','SUM(Compra.superficie) AS superficie','Compra.periodo','Compra.imputacion','Compra.tipogasto_id'),
									'conditions'=>array(
										'Compra.tipogasto_id'=> array(19/*Factura de Luz*/,21/*Alquileres*/) ,
										'Compra.cliente_id'=> $myImpcli['Impcli']['cliente_id'] ,
								 		'AND'=>array(
									 				$esMayorQuePeriodoInicioCompra,
									 				$esMenorQuePeriodoFINCompra,
									 				$esMenorQuePeriodoFINCompraConsulta 
									 			)
									 		),
								 	'group'=>array(
									 	'Compra.periodo','Compra.tipogasto_id'
									 	)
							 		));
		$domicilios = $this->Domicilio->find('all',array(
								'fields' => array('SUM(Domicilio.superficie) AS superficie'),
								'conditions'=>array(
									'Domicilio.cliente_id'=> $myImpcli['Impcli']['cliente_id'] ,
									),
							 	'group'=>array(
								 	'Domicilio.cliente_id'
								 	)
								));
		$strDatePeriodo = '28-'.$periodo;
		$optionsCategoriaMax=array(
			'fields'=>array('MAX(Categoriamonotributo.vigenciadesde) AS vigenciadesde'),
			'conditions'=>array(
					"Categoriamonotributo.vigenciadesde <= '".date('d-m-Y',strtotime($strDatePeriodo))."'",
				),
			);
		$maxcategory=$this->Categoriamonotributo->find('first',$optionsCategoriaMax);
		$this->set('maxcategory',$maxcategory);
		$optionsCategoria=array(
			'conditions'=>array(
					'Categoriamonotributo.vigenciadesde'=>$maxcategory[0]['vigenciadesde']
				),
			'order'=>array('Categoriamonotributo.orden')
			);
		$categoriamonotributos = $this->Categoriamonotributo->find('all',$optionsCategoria);
		$this->set(compact('impcliid','periodo','periodoDeInicio','ventas','compras','domicilios','categoriamonotributos','actividadclientes'));


    }
    public function papeldetrabajoddjj($periodo=null,$impcliid=null){
        $this->loadModel('Venta');
        $this->loadModel('Compra');
        $this->loadModel('Actividadcliente');
        $this->loadModel('Puntosdeventa');
        $this->loadModel('Comprobante');
        $this->loadModel('Domicilio');

        $strDatePeriodo='01-'.$periodo;
        $mesPeriodo = date('m',strtotime($strDatePeriodo));
        $añoPeriodo = date('Y',strtotime($strDatePeriodo));

        $mesinicioDelAño = '01';
        $añoinicioDelAño = $añoPeriodo;
        $mesinicioDelCuatrimestre= '01';

        $options = array('conditions' => array('Impcli.' . $this->Impcli->primaryKey => $impcliid));
        $myImpcli = $this->Impcli->find('first', $options);
        $this->set('impcli',$myImpcli);

        switch ($mesPeriodo) {
            case '01':
                $mesinicioDelCuatrimestre = '01';
                $mesinicioDelAño= '05';
                $añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
                break;
            case '02':
                $mesinicioDelCuatrimestre = '01';
                $mesinicioDelAño= '05';
                $añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
                break;
            case '03':
                $mesinicioDelCuatrimestre = '01';
                $mesinicioDelAño= '05';
                $añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
                break;
            case '04':
                $mesinicioDelCuatrimestre = '01';
                $mesinicioDelAño= '05';
                $añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
                break;
            case '05':
                $mesinicioDelCuatrimestre = '05';
                $mesinicioDelAño= '09';
                $añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
                break;
            case '06':
                $mesinicioDelCuatrimestre = '05';
                $mesinicioDelAño= '09';
                $añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
                break;
            case '07':
                $mesinicioDelCuatrimestre = '05';
                $mesinicioDelAño= '09';
                $añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
                break;
            case '08':
                $mesinicioDelCuatrimestre = '05';
                $mesinicioDelAño= '09';
                $añoinicioDelAño = date('Y',strtotime($strDatePeriodo.' -1 year'));
                break;
            case '09':
                $mesinicioDelCuatrimestre = '09';
                $mesinicioDelAño= '01';
                break;
            case '10':
                $mesinicioDelCuatrimestre = '09';
                $mesinicioDelAño= '01';
                break;
            case '11':
                $mesinicioDelCuatrimestre = '09';
                $mesinicioDelAño= '01';
                break;
            case '12':
                $mesinicioDelCuatrimestre = '09';
                $mesinicioDelAño= '01';
                break;
            default:
                # code...
                break;
        }
		$this->set('periodoInicioCuatrimestre',$mesinicioDelCuatrimestre.'-'.$añoPeriodo);
        $esMenorQuePeriodoFINConsulta = array(
            'OR'=>array(
                'SUBSTRING(Venta.periodo,4,7)*1 < '.$añoPeriodo.'*1',
                'AND'=>array(
                    'SUBSTRING(Venta.periodo,4,7)*1 <= '.$añoPeriodo.'*1',
                    'SUBSTRING(Venta.periodo,1,2) <= '.$mesPeriodo.'*1'
                ),
            )
        );
        $esMayorQuePeriodoInicioCuatrimestre = array(
            //Periodo FIN es mayor que el periodo
            'OR'=>array(
                'SUBSTRING(Venta.periodo,4,7)*1 > '.$añoPeriodo.'*1',
                'AND'=>array(
                    'SUBSTRING(Venta.periodo,4,7)*1 >= '.$añoPeriodo.'*1',
                    'SUBSTRING(Venta.periodo,1,2) >= '.$mesinicioDelCuatrimestre.'*1'
                ),
            )
        );
        $optionVentastop=array(
            'contain'=>array(
                'Subcliente'=>array(
                    'fields'=>array('Subcliente.id,Subcliente.nombre','Subcliente.cuit')
                )
            ),
            'conditions'=>array(
                'Venta.cliente_id'=> $myImpcli['Impcli']['cliente_id'] ,
                'AND'=>array(
                    $esMayorQuePeriodoInicioCuatrimestre,
                    $esMenorQuePeriodoFINConsulta
                )
            ),
            'fields'=>array('subcliente_id,SUM(Venta.total) as total,COUNT(Venta.total) as cantidad'),
            'group'=>array('Venta.subcliente_id'),
            'limit' => 5
        );
        $ventastop=$this->Venta->find('all',$optionVentastop);
        $esMayorQuePeriodoInicioCuatrimestreCompra = array(
            //Periodo FIN es mayor que el periodo
            'OR'=>array(
                'SUBSTRING(Compra.periodo,4,7)*1 > '.$añoPeriodo.'*1',
                'AND'=>array(
                    'SUBSTRING(Compra.periodo,4,7)*1 >= '.$añoPeriodo.'*1',
                    'SUBSTRING(Compra.periodo,1,2) >= '.$mesinicioDelCuatrimestre.'*1'
                ),
            )
        );
        $esMenorQuePeriodoFINCompraConsulta = array(
            'OR'=>array(
                'SUBSTRING(Compra.periodo,4,7)*1 < '.$añoinicioDelAño.'*1',
                'AND'=>array(
                    'SUBSTRING(Compra.periodo,4,7)*1 <= '.$añoinicioDelAño.'*1',
                    'SUBSTRING(Compra.periodo,1,2) <= '.$mesinicioDelAño.'*1'
                ),
            )
        );
        $optionComprastop=array(
            'contain'=>array(
                'Provedore'=>array(
                    'fields'=>array('Provedore.id,Provedore.nombre','Provedore.cuit')
                )
            ),
            'conditions'=>array(
                'Compra.cliente_id'=> $myImpcli['Impcli']['cliente_id'] ,
                'AND'=>array(
                    $esMayorQuePeriodoInicioCuatrimestreCompra,
                    $esMenorQuePeriodoFINCompraConsulta
                )
            ),
            'fields'=>array('provedore_id,SUM(Compra.total) as total,COUNT(Compra.total) as cantidad'),
            'group'=>array('Compra.provedore_id'),
            'order'=>array('total'),
            'limit' => 5
        );
        $comprastop=$this->Compra->find('all',$optionComprastop);

        $optionPuntosdeventas = array(
            'conditions'=>array('Puntosdeventa.cliente_id'=> $myImpcli['Impcli']['cliente_id']),
            'fields'=>array('id','nombre','sistemafacturacion')
        );
        $puntosdeventas = $this->Puntosdeventa->find('all',$optionPuntosdeventas);

        // Primera y ultima factura por tipocomprobante y punto de venta
        $optionsventas=array(
            'contain'=>array(
                'Comprobante'=>array('id','nombre','tipodebitoasociado'),
                'Puntosdeventa'=>array('id','nombre'),//
            ),
            'fields'=>array(
                'MAX(numerocomprobante) as maxnumerocomprobante',
                'MIN(numerocomprobante) as minnumerocomprobante',
                'SUM(total) as total',
            ),
            'conditions'=>array(
                'Venta.cliente_id'=> $myImpcli['Impcli']['cliente_id'] ,
                'AND'=>array(
                    $esMayorQuePeriodoInicioCuatrimestre,
                    $esMenorQuePeriodoFINConsulta
                )
            ),
            'group'=>array('Venta.puntosdeventa_id','Venta.comprobante_id'),
            'limit' => 5,
            'order'=>array('total')
        );
        $ultimasventas = $this->Venta->find('all',$optionsventas);
        $this->set('ultimasventas',$ultimasventas);
        $domicilios = $this->Domicilio->find('list',array(
            'conditions'=>array(
                'Domicilio.cliente_id'=> $myImpcli['Impcli']['cliente_id'] ,
            ),
        ));
        //Kilowats
        $compraskw = $this->Compra->find('all',array(
            'fields' => array('SUM(Compra.total) AS total','SUM(Compra.kw) AS kw','SUM(Compra.superficie) AS superficie','Compra.periodo','Compra.imputacion','Compra.tipogasto_id'),
            'conditions'=>array(
                'Compra.tipogasto_id'=> array(19/*Factura de Luz*/,21/*Alquileres*/) ,
                'Compra.cliente_id'=> $myImpcli['Impcli']['cliente_id'] ,
                'AND'=>array(
                    $esMayorQuePeriodoInicioCuatrimestreCompra,
                    $esMenorQuePeriodoFINCompraConsulta
                )
            ),
            'group'=>array(
                'Compra.periodo','Compra.tipogasto_id'
            )
        ));

        $this->set(compact('ventastop','comprastop','puntosdeventas','comprobantes','domicilios','compraskw'));
        $this->layout = 'ajax';
    }
	public function papeldetrabajosuss($impcliid=null,$periodo=null){
		$optionsImpCliSolic = array(
			'contain' => array('Impuesto'),
			'conditions' => array('Impcli.' . $this->Impcli->primaryKey => $impcliid)
		);
		//Impuesto Solicitado (por ef FAESYS)
		//Impuesto a Liquidar (Por ejemplo SEC)
		$impcliSolicitado = $this->Impcli->find('first', $optionsImpCliSolic);

		$options = array(
			'contain'=>array(
				'Cliente'=>array(
					'Empleado'=>array(
						'Valorrecibo'=>array(
							'Cctxconcepto'=>array(
								'Concepto',
								'Conveniocolectivotrabajo'=>array(

								)
							),
							'conditions'=>array(
								'Valorrecibo.periodo'=>$periodo,
								'Valorrecibo.tipoliquidacion'=>array(1,2,3)
							)
						),
						'conditions' => array('Empleado.cliente_id' => $impcliSolicitado['Impcli']['cliente_id'])
					)
				),
			),
			'conditions' => array('Impcli.' . $this->Impcli->primaryKey => $impcliid));
		$impcli = $this->Impcli->find('first', $options);
		$this->set('impcli',$impcli);

		$this->set(compact('impcliid','periodo'));
		$optionsSUSS = array(
			'351ContribucionesSegSocial' => '351 Contribuciones Seg. Social',
			'301EmpleadorAportesSegSocial' => '301 Empleador - Aportes Seg. Social',
			'360ContribuciónRENATEA' => '360 Contribución RENATEA',
			'352ContribucionesObraSocial' => '352 Contribuciones Obra Social',
			'935RENATEA' => '935 RENATEA',
			'302AportesObrasSociales' => '302 Aportes Obras Sociales',
			'270ContribValesAlimentl24700' => '270 Contrib. Vales Aliment.l.24700',
			'312AsegRiesgodeTrabajoL24557' => '312 Aseg. Riesgo de Trabajo L 24557',
			'28SegurodeVidaColectivo'=>'28 Seguro de Vida Colectivo' ,
		);
		$this->set('optionsSUSS',$optionsSUSS);
	}
	public function papeldetrabajosindicatos($impcliid=null,$periodo=null){
		$this->loadModel('Conceptosrestante');
		//Aca vamos a controlar que el sindicato que estamos por liquidar
		//sea un sindicato con Convenios y no uno que apunte a otro sindicato.
		//Y si es un sindicato que apunta a otro sindicato buscar el "otro sindicato" para liquidar el primero.
		//Por ejemplo el sindicato SEC tiene CCT(convenio colectivo de trabajo) Comercio, pero los empleados que estan en el convenio de comercio
		//pagan FAESYS tambien, pero cuando liquidamos FAESYS no tenemos convenios asociados, por eso Faesys apuntara a SEC para su liquidacion
		$optionsImpCliSolic = array(
			'contain' => array('Impuesto'),
			'conditions' => array('Impcli.' . $this->Impcli->primaryKey => $impcliid)
		);
		//Impuesto Solicitado (por ef FAESYS)
		//Impuesto a Liquidar (Por ejemplo SEC)

		$impcliSolicitado = $this->Impcli->find('first', $optionsImpCliSolic);
		$impcliIdALiquidar = $impcliSolicitado['Impcli']['id'];
		if($impcliSolicitado['Impuesto']['delegado']){
			//aca vamos a tener que buscar un Impcli con el cliente_id del solicitado y el Impuesto_id del liquidado
			$optionsImpCliSolic = array(
				'contain' => array('Impuesto'),
				'conditions' => array('Impcli.cliente_id'=> $impcliid)
			);
			$impcliIdAUsar = $impcliSolicitado['Impuesto']['delegadoId'];
		}
		$this->set('impcliSolicitado',$impcliSolicitado);

		$options = array(
			'contain'=>array(
				'Cliente',
				'Impuesto'=>array(
					'Conveniocolectivotrabajo'=>array(
						'Empleado'=>array(
							'Puntosdeventa'=>array(
								'Domicilio'=>array(
									'Localidade'=>array(
										'Partido'
									)
								)
							),
							'Valorrecibo'=>array(
								'Cctxconcepto'=>array(
									'Concepto',
									'Conveniocolectivotrabajo'=>array(

									)
								),
								'conditions'=>array(
									'Valorrecibo.periodo'=>$periodo
								)
							),
						)
					),
				),
			),
			'conditions' => array('Impcli.' . $this->Impcli->primaryKey => $impcliid));
		$impcli = $this->Impcli->find('first', $options);
		$this->set('impcli',$impcli);

		$this->set(compact('impcliid','periodo'));
		$conditionsConceptosrestantes=array(
			'contain'=>array(

			),
			'conditions'=>array(
				'Conceptosrestante.periodo'=>$periodo,
				'Conceptosrestante.impcli_id'=>$impcliid,
			)
		);
		$conceptosrestantes = $this->Conceptosrestante->find('all',$conditionsConceptosrestantes);
		$this->set('conceptosrestantes',$conceptosrestantes);

		$timePeriodo = strtotime("01-".$periodo ." -1 months");
		$periodoPrevio = date("m-Y",$timePeriodo);

		$conditionsConceptosrestantes=array(
			'contain'=>array(

			),
			'conditions'=>array(
				'Conceptosrestante.periodo'=>$periodoPrevio,
				'Conceptosrestante.impcli_id'=>$impcliid,
				'Conceptosrestante.conceptostipo_id'=>'1',
			)
		);
		$conceptosrestantesafavor = $this->Conceptosrestante->find('all',$conditionsConceptosrestantes);
		$this->set('impcliSaldoAFavor',$conceptosrestantesafavor);
	}
	public function papeldetrabajocooperadoraasistencial($impcliid=null,$periodo=null){
        $this->loadModel('Conceptosrestante');
        $options = array(
			'contain'=>array(
				'Cliente'=>array(
					'Empleado'=>array(
						'Puntosdeventa'=>array(
							'Domicilio'=>array(
								'Localidade'=>array(
									'Partido'
								)
							)
						),
						'Valorrecibo'=>array(
							'Cctxconcepto'=>array(
								'Concepto'
							),
							'conditions'=>array(
								'Valorrecibo.periodo'=>$periodo
							)
						),
					)
				),
			),
			'conditions' => array('Impcli.' . $this->Impcli->primaryKey => $impcliid));
		$impcli = $this->Impcli->find('first', $options);
		$this->set('impcli',$impcli);

		$this->set(compact('impcliid','periodo'));
        $conditionsConceptosrestantes=array(
            'contain'=>array(

            ),
            'conditions'=>array(
                'Conceptosrestante.periodo'=>$periodo,
                'Conceptosrestante.impcli_id'=>$impcliid,
            )
        );
        $conceptosrestantes = $this->Conceptosrestante->find('all',$conditionsConceptosrestantes);
        $this->set('conceptosrestantes',$conceptosrestantes);

        $timePeriodo = strtotime("01-".$periodo ." -1 months");
        $periodoPrevio = date("m-Y",$timePeriodo);

        $conditionsConceptosrestantes=array(
            'contain'=>array(

            ),
            'conditions'=>array(
                'Conceptosrestante.periodo'=>$periodoPrevio,
                'Conceptosrestante.impcli_id'=>$impcliid,
                'Conceptosrestante.conceptostipo_id'=>'1',
            )
        );
        $conceptosrestantesafavor = $this->Conceptosrestante->find('all',$conditionsConceptosrestantes);
        $this->set('impcliSaldoAFavor',$conceptosrestantesafavor);
	}
}
