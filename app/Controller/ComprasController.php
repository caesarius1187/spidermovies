<?php
App::uses('AppController', 'Controller');
/**
 * Compras Controller
 *
 * @property Compra $Compra
 * @property PaginatorComponent $Paginator
 */
class ComprasController extends AppController {

	public $alicuotas = array("0" => '0',"2.5" => '2.5',"5" => '5',"10.5" => '10.5',"21" => '21',"27" => '27',);
	public $alicuotascodigo = array("0" => '0% 0003',"2.5" => '2.5% 0001',"5" => '5% 0002',"10.5" => '10.5% 0004',"21" => '21% 0005',"27" => '27% 0006',);
	public $condicionesiva = array("monotributista" => 'Monotributista',"responsableinscripto" => 'Responsable Inscripto','consf/exento/noalcanza'=>"Cons. F/Exento/No Alcanza",);
	public $tipocreditos = array('Credito Fiscal'=>'Credito Fiscal','Restitucion credito fiscal'=>'Restitucion credito fiscal');
	public $imputaciones=array(
				'Bs en Gral'=>'Bs en Gral',
				'Locaciones'=>'Locaciones',
				'Prest. Servicios'=>'Prest. Servicios',
				'Bs Uso'=>'Bs Uso',
				'Otros Conceptos'=>'Otros Conceptos',
				'Dcto 814'=>'Dcto 814'
	);

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
		$this->Compra->recursive = 0;
		$this->set('compras', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Compra->exists($id)) {
			throw new NotFoundException(__('Invalid compra'));
		}
		$options = array('conditions' => array('Compra.' . $this->Compra->primaryKey => $id));
		$this->set('compra', $this->Compra->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Compra->create();
			if ($this->Compra->save($this->request->data)) {
				$this->Session->setFlash(__('The compra has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The compra could not be saved. Please, try again.'));
			}
		}
		$clientes = $this->Compra->Cliente->find('list');
		$subclientes = $this->Compra->Subcliente->find('list');
		$localidades = $this->Compra->Localidade->find('list');
		$this->set(compact('clientes', 'subclientes', 'localidades'));
	}

/**
 * add method
 *
 * @return void
 */
	public function addajax(){
	 	//$this->request->onlyAllow('ajax');
	 	$this->loadModel('Subcliente');
	 	$this->loadModel('Localidade');
	 	$this->loadModel('Comprobante');
	 	$this->loadModel('Provedore');
	 	$this->loadModel('Tipogasto');
	 	$this->loadModel('Actividadcliente');
	 	$this->autoRender=false; 
	 	if ($this->request->is('post')) { 		
			$optionsCompra = array(
					'Compra.cliente_id'=>$this->request->data['Compra']['cliente_id'],
					'Compra.comprobante_id'=>$this->request->data['Compra']['comprobante_id'],
					'Compra.puntosdeventa'=>$this->request->data['Compra']['puntosdeventa'],
					'Compra.numerocomprobante'=>$this->request->data['Compra']['numerocomprobante'],
					'Compra.alicuota'=>$this->request->data['Compra']['alicuota'],
					'Compra.provedore_id'=>$this->request->data['Compra']['provedore_id'],
				);
			$compraAnterior = $this->Compra->hasAny($optionsCompra);
			
			if($compraAnterior){
				$data = array(
		            "respuesta" => "La Compra ".$this->request->data['Compra']['numerocomprobante']." ya ha sido creada. Por favor cambie el numero de comprobante o la alicuota",
		            "compra_id" => 0,
		            "compra"=> array(),		            
		        );
		        $this->layout = 'ajax';
		        $this->set('data', $data);
				$this->render('serializejson');
				return ;
	 		}
			
			$this->Compra->create();
			if($this->request->data['Compra']['fecha']!=""){
				$this->request->data('Compra.fecha',date('Y-m-d',strtotime($this->request->data['Compra']['fecha'])));
			}				
			if ($this->Compra->save($this->request->data)) {
				$optionsComprobante = array('conditions'=>array('Comprobante.id' => $this->request->data['Compra']['comprobante_id']));
				$optionsTipoGasto = array('conditions'=>array('Tipogasto.id' => $this->request->data['Compra']['tipogasto_id']));
				$optionsProverode = array('conditions'=>array('Provedore.id'=>$this->request->data['Compra']['provedore_id']));
				$optionsLocalidade = array('conditions'=>array('Localidade.id'=>$this->request->data['Compra']['localidade_id']));
				$optionsActividadCliente = array('conditions'=>array('Actividadcliente.id'=>$this->request->data['Compra']['actividadcliente_id']));
				$this->Provedore->recursive = -1;
				$this->Localidade->recursive = -1;

				$data = array(
		            "respuesta" => "La Compra ha sido creada.",
		            "compra_id" => $this->Compra->getLastInsertID(),
		            "compra"=> $this->request->data,
		            "comprobante"=> $this->Comprobante->find('first',$optionsComprobante),
		            "tipogasto"=> $this->Tipogasto->find('first',$optionsTipoGasto),
		            "provedore"=> $this->Provedore->find('first',$optionsProverode),
		            "localidade"=> $this->Localidade->find('first',$optionsLocalidade),
		            "actividadcliente"=> $this->Actividadcliente->find('first',$optionsActividadCliente),
		            "actividadcliente_id"=> $this->request->data['Compra']['actividadcliente_id'],
		            /*AFIP*/
		            "tieneMonotributo"=> $this->request->data['Compra']['tieneMonotributo'],
		            "tieneIVA"=> $this->request->data['Compra']['tieneIVA'],
		            "tieneIVAPercepciones"=> $this->request->data['Compra']['tieneIVAPercepciones'],
		            "tieneImpuestoInterno"=> $this->request->data['Compra']['tieneImpuestoInterno'],
			        /*DGR*/
		            "tieneAgenteDePercepcionIIBB"=> $this->request->data['Compra']['tieneAgenteDePercepcionIIBB'],
			        /*DGRM*/
		            "tieneAgenteDePercepcionActividadesVarias"=> $this->request->data['Compra']['tieneAgenteDePercepcionActividadesVarias'],
		        );
			}
			else{
				$data = array(
		        	"respuesta" => "La Compra NO ha sido creada.Intentar de nuevo mas tarde",
		            "venta_id" => $this->Compra->getLastInsertID()
		        );
			}
			$this->layout = 'ajax';
	        $this->set('data', $data);
			$this->render('serializejson');
			
		}
	}
	public function agregarparaimportar()
	{
		$this->autoRender=false;
		$this->loadModel('Provedore');

		if (!empty($this->data)) {
			$this->Provedore->create();
			$allSaved = true;

			foreach($this->data as $model => $data) {
				switch ($model) {
					case 'Provedore' :
						/*manipulate ModelName1 data here*/
						if (!$this->Provedore->saveAll($this->data['Provedore']))
							$allSaved = false;
						break;
				}
			}

			if ($allSaved) {
				/*Print Success Message*/
			}
			if ($allSaved) {
				$this->Session->setFlash(__('Se han guardado los provedores.'));
				if(count($this->request->data['Provedore']>0)){
					$this->redirect(
						array('action' => 'importar',
							reset($this->request->data['Provedore'])['cliente_id'],
							reset($this->request->data['Provedore'])['periodo'],
							'subcliente')
					);
				}
			} else {
				$this->Session->setFlash(__('Error al guardar.'));
				$this->log(json_encode($this->request->data));
				$this->log(json_encode($this->Provedore->validationErrors));
				$this->log(json_encode($this->Provedore->invalidFields()));
				$this->log(json_encode($this->Provedore->getDataSource()->getLog(true, true)));

				$this->redirect(
					array(
						'controller' => 'clientes',
						'action' => 'avance'
					)
				);
			}
		}else{
			$this->Session->setFlash(__('Datos vacios.'));
			$this->redirect(
				array(
					'action' => 'index'
				)
			);
		}

	}
	public function importar($cliid=null,$periodo=null){
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		$this->loadModel('Provedore');
		$this->loadModel('Localidade');
		$this->loadModel('Partido');
		$this->loadModel('Actividadcliente');
		$this->loadModel('Comprobante');
		$this->loadModel('Domicilio');
		$this->loadModel('Cliente');

		$folderCompras = WWW_ROOT.'files'.DS.'compras'.DS.$cliid.DS.$periodo.DS.'compras';
		$folderAlicuotas = WWW_ROOT.'files'.DS.'compras'.DS.$cliid.DS.$periodo.DS.'alicuotas';
		if ($this->request->is('post')) {
			$folderCompras = WWW_ROOT.'files'.DS.'compras'.DS.$this->request->data['Compra']['cliid'].DS.$this->request->data['Compra']['periodo'].DS.'compras';
			$folderAlicuotas = WWW_ROOT.'files'.DS.'compras'.DS.$this->request->data['Compra']['cliid'].DS.$this->request->data['Compra']['periodo'].DS.'alicuotas';
			$fileNameCompra = null;
			$tmpNameCompra= $this->request->data['Compra']['archivocompra']['tmp_name'];
			if (!empty($tmpNameCompra)&& is_uploaded_file($tmpNameCompra)) {
				// Strip path information
				$fileNameCompra = $this->request->data['Compra']['archivocompra']['name'];
				move_uploaded_file($tmpNameCompra, $folderCompras.DS.$fileNameCompra);
			}
			$fileAlicuota = null;
			$tmpNameAlicuota= $this->request->data['Compra']['archivoalicuota']['tmp_name'];
			if (!empty($tmpNameAlicuota)&& is_uploaded_file($tmpNameAlicuota)) {
				// Strip path information
				$fileAlicuota = $this->request->data['Compra']['archivoalicuota']['name'];
				move_uploaded_file($tmpNameAlicuota, $folderAlicuotas.DS.$fileAlicuota);
			}
		}
		$conditionsProvedores = array('Provedore.cliente_id' => $cliid,);
		$provedores = $this->Provedore->find('list',array('conditions' =>$conditionsProvedores));
		$this->set(compact('provedores'));

		$conditionsLocalidades = array(
			'contain'=>'Partido',
			'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
			'order'=>array('Partido.nombre','Localidade.nombre')
		);
		$localidades = $this->Localidade->find('list',$conditionsLocalidades);
		$this->set('localidades', $localidades);

		$partidos = $this->Partido->find('list');
		$this->set('partidos', $partidos);


		$comprobantes = $this->Comprobante->find('list');
		$this->set('comprobantes', $comprobantes);

		$miscomprobantes = $this->Comprobante->find('all',array('contain'=>array()));
		$this->set('miscomprobantes ', $miscomprobantes);

		$this->set('alicuotas', $this->alicuotascodigo);

		$this->set('condicionesiva',$this->condicionesiva);
		$this->set('tipocreditos', $this->tipocreditos);
		$this->set('imputaciones', $this->imputaciones);

		$optionsTipoGastos=array('conditions'=>array());
		$tipogastos = $this->Compra->Tipogasto->find('list',$optionsTipoGastos);
		$this->set('tipogastos', $tipogastos);

		$clienteActividadList=$this->Actividadcliente->find('list', array(
				'contain' => array(
					'Actividade'
				),
				'conditions' => array(
					'Actividadcliente.cliente_id' =>  $cliid,
				),
				'fields' => array(
					'Actividadcliente.id','Actividade.nombre'
				)
			)
		);
		$this->set('actividades', $clienteActividadList);
		$optionSisFact=array(
			'Controlador Fiscal'=>'Controlador Fiscal',
			'Factuweb'=>'Factuweb (Imprenta) ',
			'RECE'=>'RECE para aplicativo y web services',
			'Factura en Linea'=>'Factura en Linea'
		);
		$this->set('optionSisFact', $optionSisFact);
		$optionsDoc = array(
			'conditions' => array('Domicilio.cliente_id'=>$cliid ),
		);
		$domicilios = $this->Domicilio->find('list',$optionsDoc);
		$this->set('domicilios', $domicilios);
		$cliente=$this->Cliente->find('first', array(
				'contain'=>array(
					'Domicilio'
				),
				'conditions' => array(
					'id' => $cliid,
				),
			)
		);
		$this->set(compact('cliente','cliid','periodo','folderCompras','folderAlicuotas','miscomprobantes'));

		$optionscomprasdelperiodo=array(
			'contain'=>array(
			),
			'fields'=>array(
			),
			'conditions'=>array(
				'Compra.periodo'=>$periodo,
				'Compra.cliente_id'=>$cliid
			)
		);
		$comprasperiodo = $this->Compra->find('all',$optionscomprasdelperiodo);
		$this->set('comprasperiodo',$comprasperiodo);
	}
	public function cargarcompras(){
		$data=array();
		if ($this->request->is('post')) {
			$params = array();
			$myParser = new ParserUnlimited();
            //Debugger::dump($this->request->data);
            $myParser->my_parse_str($this->request->data['Compra'][0]['jsonencript'],$params);
            foreach ($params['data']['Compra'] as $k => $micompra){
                $mifecha = $micompra['fecha'];
                $params['data']['Compra'][$k]['fecha'] = date('Y-m-d',strtotime($mifecha));
            }
			$this->Compra->create();
            if ($this->Compra->saveAll($params['data']['Compra'])) {
            //if (1==1) {
                    $data['respuesta'] = 'Las Compras han sido guardadas.';
                } else {
                    $data['respuesta'] = 'Error al guardar compras, por favor intende de nuevo mas tarde.';
                }
		}else{
			$data['respuesta'] = 'acceso denegado';
		}
		$this->layout = 'ajax';
		$this->set('data', $data);
		$this->render('serializejson');


		//return $this->redirect(array('controller'=>'compras', 'action' => 'importar',$this->request->data['Compra'][0]['cliente_id'],$this->request->data['Compra'][0]['periodo']));
	}
	public function deletefile($name=null,$cliid=null,$folder=null,$periodo=null){
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		$file = WWW_ROOT.'files'.DS.'compras'.DS.$cliid.DS.$periodo.DS.$folder.DS.$name;
		chmod($file, 0777);
		if( is_file( $file ) AND is_readable( $file ) ) {
			if (unlink($file)) {
				$this->Session->setFlash(__('El Archivo ha sido eliminado'));
			} else {
				$this->Session->setFlash(__('El Archivo NO ha sido eliminado.Por favor nuevamente intente mas tarde.'));
			}
		}else{
			$this->Session->setFlash(__('No se puede acceder al archivo'.$file));
		}
		return $this->redirect(
			array(
				'controller'=>'compras',
				'action' => 'importar',
				$cliid,
				$periodo
			)
		);
	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null,
		$tieneMonotributo = null,
		$tieneIVAPercepciones = null, 
		$tieneImpuestoInterno = null, 
		$tieneIVA = null, 
		$tieneAgenteDePercepcionIIBB = null, 
		$tieneAgenteDePercepcionActividadesVarias = null
		) 
		{
		$this->loadModel('Provedore');
		$this->loadModel('Localidade');
		$this->loadModel('Partido');
		$this->loadModel('Puntosdeventa');
		if (!$this->Compra->exists($id)) {
			throw new NotFoundException(__('Compra No Existe'));
			return;
		}
		$mostrarForm=true;
		$compraAnterior=false;
		if(!empty($this->data)){ 
			$id = $this->request->data['Compra']['id'];
			//Antes de guardar vamos a revisar que el comprobante o la alicuota cambie y tenemos que controlar que no estemos guardando una venta igual a otra que ya este guardada
			$options = array(
				'contain'=>array( ),
				'conditions' => array('Compra.' . $this->Compra->primaryKey => $id)
				);
			$compra = $this->Compra->find('first', $options);
			
			if(
				$compra['Compra']['comprobante_id'] != $this->request->data['Compra']['comprobante_id']||
				$compra['Compra']['puntosdeventa'] != $this->request->data['Compra']['puntosdeventa']||
				$compra['Compra']['numerocomprobante'] != $this->request->data['Compra']['numerocomprobante']||
				$compra['Compra']['alicuota'] != $this->request->data['Compra']['alicuota']
			)
			{
				$optionsCompra = array(
					'Compra.cliente_id'=>$this->request->data['Compra']['cliente_id'],
					'Compra.comprobante_id'=>$this->request->data['Compra']['comprobante_id'],
					'Compra.puntosdeventa'=>$this->request->data['Compra']['puntosdeventa'],
					'Compra.numerocomprobante'=>$this->request->data['Compra']['numerocomprobante'],
					'Compra.alicuota'=>$this->request->data['Compra']['alicuota'],
				);
				$compraAnterior = $this->Compra->hasAny($optionsCompra);
			}
			if(!$compraAnterior){
				$this->request->data['Compra']['fecha'] = $this->request->data['Compra']['fecha'.$id];
				$this->request->data('Compra.fecha',date(
					'Y-m-d',
					strtotime(
						$this->request->data['Compra']['fecha']
						)
					)
				);
				if ($this->Compra->save($this->request->data)) {

				} else {

				}
				$options = array(
					'contain'=>array(
						'Actividadcliente'=>array(
				   						'Actividade',
				   					),
						'Provedore',
						'Localidade'=>array('Partido'),
						'Comprobante',
					),
					'conditions' => array('Compra.' . $this->Compra->primaryKey => $id)
					);
				$compra = $this->Compra->find('first', $options);
				$compra['Compra']['tieneMonotributo'] = $this->request->data['Compra']['tieneMonotributo'];
				$compra['Compra']['tieneIVAPercepciones'] = $this->request->data['Compra']['tieneIVAPercepciones'];
				$compra['Compra']['tieneImpuestoInterno'] = $this->request->data['Compra']['tieneImpuestoInterno'];
				$compra['Compra']['tieneAgenteDePercepcionActividadesVarias'] = $this->request->data['Compra']['tieneAgenteDePercepcionActividadesVarias'];
				$compra['Compra']['tieneIVA'] = $this->request->data['Compra']['tieneIVA'];
				$compra['Compra']['tieneAgenteDePercepcionIIBB'] = $this->request->data['Compra']['tieneAgenteDePercepcionIIBB'];
				$this->set('compra',$compra);
			}else{
				$data = array(
		            "respuesta" => "La Compra ".$this->request->data['Compra']['numerocomprobante']." ya ha sido creada. Por favor cambie el numero de comprobante o la alicuota",
		            "compra_id" => 0,
		            "compra"=> array(),		            
		        );
				$this->set('data',$data);
		        $this->layout = 'ajax';
				$this->render('serializejson');
			}			
			$mostrarForm=false;			
		}else{
			$this->set('tieneMonotributo', $tieneMonotributo);
			$this->set('tieneIVAPercepciones', $tieneIVAPercepciones);
			$this->set('tieneImpuestoInterno', $tieneImpuestoInterno);
			$this->set('tieneAgenteDePercepcionActividadesVarias', $tieneAgenteDePercepcionActividadesVarias);
			$this->set('tieneIVA', $tieneIVA);
			$this->set('tieneAgenteDePercepcionIIBB', $tieneAgenteDePercepcionIIBB);
		}
		$this->set('mostrarForm',$mostrarForm);	
		$options = array(
					'contain'=>array(
						'Actividadcliente'=>array(
				   						'Actividade',
				   					),
						'Provedore',
						'Localidade'=>array('Partido'),
						'Tipogasto',
						'Comprobante',
					),
					'conditions' => array('Compra.' . $this->Compra->primaryKey => $id)
					);
		$this->request->data = $this->Compra->find('first', $options);
		$this->set('comid',$id);

		$conditionsProvedores = array('Provedore.cliente_id' => $this->request->data['Compra']['cliente_id'],);
		$provedores = $this->Compra->Provedore->find('list',array(
			'conditions' =>$conditionsProvedores,
			));	
		$this->set(compact('provedores'));

		$this->set('condicionesiva', $this->condicionesiva);

		$clienteActividadList=$this->Compra->Actividadcliente->find('list', array(
												'contain' => array(
													'Actividade'
													),
											   'conditions' => array(
												                'Actividadcliente.cliente_id' => $this->request->data['Compra']['cliente_id'], 
												            ),
											   'fields' => array(
													'Actividadcliente.id','Actividade.nombre'
														)
											)
									);	
		$this->set('actividades', $clienteActividadList);

		$tipocreditos = array('Credito Fiscal'=>'Credito Fiscal','Restitucion credito fiscal'=>'Restitucion credito fiscal');
		$this->set('tipocreditos', $tipocreditos);

		$conditionsLocalidades = array(
			'contain'=>'Partido',
			'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
			'order'=>array('Partido.nombre','Localidade.nombre')
		);
		$localidades = $this->Localidade->find('list',$conditionsLocalidades);
		$this->set('localidades', $localidades);
		
		$partidos = $this->Partido->find('list');
		$this->set('partidos', $partidos);

		$alicuotas = array("0" => '0',"2.5" => '2.5',"5" => '5',"10.5" => '10.5',"21" => '21',"27" => '27',);
		$this->set('alicuotas', $alicuotas);

		$comprobantes = $this->Compra->Comprobante->find('list',array('contain'=>array()));
		$this->set('comprobantes', $comprobantes);
		
		$imputaciones=array('Bs en Gral'=>'Bs en Gral','Locaciones'=>'Locaciones','Prest. Servicios'=>'Prest. Servicios','Bs Uso'=>'Bs Uso','Otros Conceptos'=>'Otros Conceptos','Dcto 814'=>'Dcto 814');
		$this->set('imputaciones', $imputaciones);
		
		$optionsTipoGastos=array('conditions'=>array());
		$tipogastos = $this->Compra->Tipogasto->find('list',$optionsTipoGastos);

		$this->set('tipogastos', $tipogastos);
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
		$this->Compra->id = $id;
		if (!$this->Compra->exists()) {
			throw new NotFoundException(__('Invalid Compra'));
		}
		$this->request->onlyAllow('post');
		$data = array();
		if ($this->Compra->delete()) {
			$data['respuesta'] = 'Compra eliminada con exito.';
			$data['error'] = 0;
		} else {
			$data['respuesta'] = 'Compra NO eliminada. Por favor intente mas tarde.';
			$data['error'] = 1;

		}
		$this->layout = 'ajax';
        $this->set('data', $data);
		$this->render('serializejson');

	}

}
