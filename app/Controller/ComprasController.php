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
    public $alicuotascodigo = [
        "0" => '0% 0003',
        "2.5" => '2.5% 0001',
        "5" => '5% 0002',
        "10.5" => '10.5% 0004',
        "21" => '21% 0005',
        "27" => '27% 0006'
    ];
    public $alicuotascodigoreverse = [
        '0003' =>  "0" ,
        '0001' => "2.5",
        '0002' => "5",
        '0004' => "10.5",
        '0005' => "21" ,
        '0006' => "27" ,
    ];
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

	public function cargar($id=null,$periodo=null){
		$this->loadModel('Localidade');
		$this->loadModel('Partido');
		$this->loadModel('Cliente');
		$this->loadModel('Comprobante');
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
							'Partido'=>[],
							'fields'=>array('id','nombre')
						),
						'Tipogasto'=>array(
							'fields'=>array('id','nombre')
						),
						'conditions' => array(
							'Compra.periodo'=>$periodo
						),
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


		$conditionsProvedores = array('Provedore.cliente_id' => $id,);
		$provedores = $this->Cliente->Provedore->find('list',array(
			'conditions' =>$conditionsProvedores,
		));
		$this->set(compact('provedores'));

		$clienteActividadList=$this->Cliente->Actividadcliente->find('list', array(
				'contain' => array(
					'Actividade',

				),
				'conditions' => array(
					'Actividadcliente.cliente_id' => $id,
				),
				'fields' => array(
					'Actividadcliente.id','Actividade.nombre','Actividadcliente.descripcion'
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

		$tipocreditos = array('Credito Fiscal'=>'Credito Fiscal','Restitucion credito fiscal'=>'Restitucion credito fiscal');
		$this->set('tipocreditos', $tipocreditos);


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
					'Compra.puntosdeventa*1 = '.$this->request->data['Compra']['puntosdeventa']*1,
					'Compra.numerocomprobante*1 = '.$this->request->data['Compra']['numerocomprobante']*1,
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
				$optionsComprobante = array('contain'=>[],'conditions'=>array('Comprobante.id' => $this->request->data['Compra']['comprobante_id']));
				$optionsTipoGasto = array('contain'=>[],'conditions'=>array('Tipogasto.id' => $this->request->data['Compra']['tipogasto_id']));
				$optionsProverode = array('contain'=>[],'conditions'=>array('Provedore.id'=>$this->request->data['Compra']['provedore_id']));
				$optionsLocalidade = [
					'contain'=>[
						'Partido'
					],
					'conditions'=>[
						'Localidade.id'=>$this->request->data['Compra']['localidade_id']
					]
				];
				$optionsActividadCliente = [
					'contain'=>['Actividade'],
					'conditions'=>[
						'Actividadcliente.id'=>$this->request->data['Compra']['actividadcliente_id'],
					]
				];

                $this->request->data('Compra.fecha',date('d-m-Y',strtotime($this->request->data['Compra']['fecha'])));
                $this->request->data['Compra']['fecha'] = date('d-m-Y',strtotime($this->request->data['Compra']['fecha']));
				$this->request->data['Compra']['created'] = date('Y-m-d hh:mm:ss');
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
		            "venta_id" => 0
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
		set_time_limit (360);
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
			$error="";
			if(is_file( $file )){
				$error.=" No es archivo.";
			}
			if(is_readable( $file ) ){
				$error.=" No se puede Leer.";
			}
			$this->Session->setFlash(__($error.": ".$file));
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
        $this->loadModel('Subcliente');
        $this->loadModel('Comprobante');
        $this->loadModel('Tipogasto');
        $this->loadModel('Actividadcliente');
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
					$optionsComprobante = array(
                        'contain'=>[],
                        'conditions'=>array('Comprobante.id' => $this->request->data['Compra']['comprobante_id'])
                    );
					$optionsTipoGasto = array('contain'=>[],'conditions'=>array('Tipogasto.id' => $this->request->data['Compra']['tipogasto_id']));
					$optionsProverode = array('contain'=>[],'conditions'=>array('Provedore.id'=>$this->request->data['Compra']['provedore_id']));
					$optionsLocalidade = [
						'contain'=>[
							'Partido'
						],
						'conditions'=>[
							'Localidade.id'=>$this->request->data['Compra']['localidade_id']
						]
					];
					$optionsActividadCliente = array('contain'=>['Actividade'],'conditions'=>array('Actividadcliente.id'=>$this->request->data['Compra']['actividadcliente_id']));

					$this->request->data('Compra.fecha',date('d-m-Y',strtotime($this->request->data['Compra']['fecha'])));
					$this->request->data['Compra']['fecha'] = date('d-m-Y',strtotime($this->request->data['Compra']['fecha']));
					$this->request->data['Compra']['created'] = $compra['Compra']['created'];

					$data = array(
						"respuesta" => "La Compra ha sido modificada.",
						"error" => "0",
						"compra_id" => $this->request->data['Compra']['id'],
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
				} else {
					$data = array(
						"respuesta" => "La Compra NO ha sido modificada. Por favor intentelo mas tarde",
						"error" => "1",
						);
				}
				$this->set('data',$data);
				$this->layout = 'ajax';
				$this->render('serializejson');
			}else{
				$data = array(
		            "respuesta" => "La Compra ".$this->request->data['Compra']['numerocomprobante']." ya ha sido creada. Por favor cambie el numero de comprobante o la alicuota",
		            "compra_id" => 0,
		            "compra"=> array(),		            
		        );
				$this->set('data',$data);
                $this->set('mostrarForm',false);
                $this->layout = 'ajax';
				//$this->render('serializejson');
                return;
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
	public function exportartxt($cliid,$periodo){
		$this->loadModel('Cliente');

        $optionsComptras=[
            'fields'=>['*','count(*) as cantalicuotas'
				,'sum(total) as total','sum(nogravados) as nogravados','sum(exentos) as exentos'
				,'sum(ivapercep) as ivapercep','sum(iibbpercep) as iibbpercep','sum(actvspercep) as actvspercep'
				,'sum(impinternos) as impinternos'],
            'contain'=>[
                'Comprobante',
                'Provedore'
            ],
            'conditions'=>[
                'Compra.cliente_id'=>$cliid,
                'Compra.periodo'=>$periodo
            ],
            'group'=>[
                'Compra.comprobante_id',
                'Compra.puntosdeventa',
                //'Compra.alicuota',
                'Compra.numerocomprobante',
                'Compra.provedore_id',
            ]
        ];

        $compras = $this->Compra->find('all',$optionsComptras);
        $optionsAlicuotas=[
            'contain'=>[
                'Comprobante',
                'Provedore'
            ],
            'conditions'=>[
                'Compra.cliente_id'=>$cliid,
                'Compra.periodo'=>$periodo
            ],

        ];

        $alicuotas = $this->Compra->find('all',$optionsAlicuotas);

		$optionCliente=[
			'contain'=>[],
			'conditions'=>['Cliente.id'=>$cliid]
		];
		$cliente = $this->Cliente->find('first',$optionCliente);

        $alicuotascodigoreverse = $this->alicuotascodigoreverse;
		$this->set(compact('compras','alicuotas','cliente','cliid','periodo','alicuotascodigoreverse'));
	}
	public function exportartxtpercepcionesiibb($cliid,$periodo){
		$this->loadModel('Cliente');
		$optionsComptras=[
			'fields'=>['*','count(*) as cantalicuotas'],
			'contain'=>[
				'Comprobante',
				'Provedore'
			],
			'conditions'=>[
				'Compra.cliente_id'=>$cliid,
				'Compra.periodo'=>$periodo,
				'Compra.iibbpercep <> 0',
			],
			'group'=>[
				'Compra.comprobante_id',
				'Compra.puntosdeventa',
				//'Compra.alicuota',
				'Compra.numerocomprobante',
				'Compra.provedore_id',
			]
		];

		$compras = $this->Compra->find('all',$optionsComptras);
		$optionCliente=[
			'contain'=>[],
			'conditions'=>['Cliente.id'=>$cliid]
		];
		$cliente = $this->Cliente->find('first',$optionCliente);
		$this->set(compact('compras','cliente','cliid','periodo'));
	}
	public function exportartxtpercepcionessifere($cliid,$periodo){
		$this->loadModel('Cliente');
		$optionsComptras=[
			'fields'=>['*','count(*) as cantalicuotas'],
			'contain'=>[
				'Localidade'=>['Partido'],
				'Comprobante',
				'Provedore'
			],
			'conditions'=>[
				'Compra.cliente_id'=>$cliid,
				'Compra.periodo'=>$periodo,
				'Compra.iibbpercep <> 0'
			],
			'group'=>[
				'Compra.comprobante_id',
				'Compra.puntosdeventa',
				//'Compra.alicuota',
				'Compra.numerocomprobante',
				'Compra.provedore_id',
			]
		];

		$compras = $this->Compra->find('all',$optionsComptras);
		$optionCliente=[
			'contain'=>[],
			'conditions'=>['Cliente.id'=>$cliid]
		];
		$cliente = $this->Cliente->find('first',$optionCliente);
		$this->set(compact('compras','cliente','cliid','periodo'));
	}
	public function exportartxtpercepcionesdgrm($cliid,$periodo){
		$this->loadModel('Cliente');
		$optionsComptras=[
			'fields'=>['*','count(*) as cantalicuotas'],
			'contain'=>[
				'Comprobante',
				'Provedore'
			],
			'conditions'=>[
				'Compra.cliente_id'=>$cliid,
				'Compra.periodo'=>$periodo,
				'Compra.actvspercep <> 0',
				'Compra.localidade_id'=>'48',/*Salta Capital*/
			],
			'group'=>[
				'Compra.comprobante_id',
				'Compra.puntosdeventa',
				//'Compra.alicuota',
				'Compra.numerocomprobante',
				'Compra.provedore_id',
			]
		];

		$compras = $this->Compra->find('all',$optionsComptras);
		$optionCliente=[
			'contain'=>[],
			'conditions'=>['Cliente.id'=>$cliid]
		];
		$cliente = $this->Cliente->find('first',$optionCliente);
		$this->set(compact('compras','cliente','cliid','periodo','optionCliente'));
	}

}
