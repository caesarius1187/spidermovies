<?php
App::uses('AppController', 'Controller');
/**
 * Ventas Controller
 *
 * @property Venta $Venta
 * @property PaginatorComponent $Paginator
 */
class VentasController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	public $alicuotas = array("0" => '0',"2.5" => '2.5',"5" => '5',"10.5" => '10.5',"21" => '21',"27" => '27',);
	public $alicuotascodigo = array("0" => '0% 0003',"2.5" => '2.5% 0001',"5" => '5% 0002',"10.5" => '10.5% 0004',"21" => '21% 0005',"27" => '27% 0006',);
	public $condicionesiva = array("monotributista" => 'Monotributista',"responsableinscripto" => 'Responsable Inscripto','consf/exento/noalcanza'=>"Cons. F/Exento/No Alcanza",);
	public $tipodebitos = array('Debito Fiscal'=>'Debito Fiscal','Bien de uso'=>'Bien de uso','Restitucion debito fiscal'=>'Restitucion debito fiscal');
	public $optionSisFact=array(
		'Controlador Fiscal'=>'Controlador Fiscal',
		'Factuweb'=>'Factuweb (Imprenta) ',
		'RECE'=>'RECE para aplicativo y web services',
		'Factura en Linea'=>'Factura en Linea'
	);
	public $alicuotascodigoreverse = [
		'0003' =>  "0" ,
		'0001' => "2.5",
		'0002' => "5",
		'0004' => "10.5",
		'0005' => "21" ,
		'0006' => "27" ,
	];

	public function index($id=null,$periodo=null,$page=null)
	{
		$this->loadModel('Cliente');
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
		if($page==null){
			$page = 1;
		}
		$cliente=$this->Cliente->find('first', array(
				'contain'=>array(
//					'Venta'=>array(
//						'fields'=>['id','fecha','condicioniva','condicioniva','numerocomprobante','alicuota','tipodebito'
//							,'neto','iva','ivapercep','iibbpercep','actvspercep','impinternos','nogravados','excentos'
//							,'exentosactividadeseconomicas','exentosactividadesvarias','total'],
//						'Puntosdeventa'=>array(
//							'fields'=>array('id','nombre')
//						),
//						'Subcliente'=>array(
//							'fields'=>array('id','nombre','cuit')
//						),
//						'Localidade'=>array(
//							'Partido',
//							'fields'=>array('id','nombre')
//						),
//						'Actividadcliente'=>array(
//							'Actividade',
//						),
//						'Comprobante'=>[
//							'fields'=>[
//								'id',
//								'tipodebitoasociado',
//								'tipocreditoasociado',
//								'nombre',
//								'abreviacion',
//								'codigo']
//						],
//						'conditions' => array(
//							'Venta.periodo'=>$periodo
//						),
//						'offset'=>$page,
//						'limit'=>1000
//					),
					'Impcli'=>[
						'Cbu'=>[
							'Movimientosbancario'=>[
								'Cbu',
								'Cuentascliente',
								'conditions'=>[
									'Movimientosbancario.periodo'=>$periodo,
								]
							],
						],
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
		$ventas=$this->Venta->find('all', array(
						'contain'=>[],
						'fields'=>['id','fecha','condicioniva','condicioniva','numerocomprobante','alicuota','tipodebito'
							,'neto','iva','ivapercep','iibbpercep','actvspercep','impinternos','nogravados','excentos'
							,'exentosactividadeseconomicas','exentosactividadesvarias','total',
							'Puntosdeventa.nombre','Subcliente.nombre','Subcliente.cuit','Localidade.nombre',
							'Partido.nombre','Actividade.nombre','Actividade.nombre','Comprobante.tipodebitoasociado'
							,'Comprobante.tipocreditoasociado','Comprobante.nombre','Comprobante.abreviacion','Comprobante.codigo'],
						'joins'=>[
							[
								'table' => 'puntosdeventas',
								'alias' => 'Puntosdeventa',
//								'fields'=> ['id','nombre'],
								'type' => 'INNER',
								'conditions' => array(
									'Puntosdeventa.id = Venta.puntosdeventa_id',
								)
							],
							[
								'table' => 'subclientes',
								'alias' => 'Subcliente',
//								'fields'=> ['id','nombre','cuit'],
								'type' => 'INNER',
								'conditions' => array(
									'Subcliente.id = Venta.subcliente_id',
								)
							],
							[
								'table' => 'localidades',
								'alias' => 'Localidade',
//								'fields'=> ['id','nombre'],
								'type' => 'INNER',
								'conditions' => array(
									'Localidade.id = Venta.localidade_id',
								)
							],
							[
								'table' => 'partidos',
								'alias' => 'Partido',
//								'fields'=> ['id','nombre'],
								'type' => 'INNER',
								'conditions' => array(
									'Partido.id = Localidade.partido_id',
								)
							],
							[
								'table' => 'actividadclientes',
								'alias' => 'Actividadcliente',
								'type' => 'INNER',
								'conditions' => array(
									'Actividadcliente.id = Venta.actividadcliente_id',
								)
							],
							[
								'table' => 'actividades',
								'alias' => 'Actividade',
								'type' => 'INNER',
								'conditions' => array(
									'Actividadcliente.actividade_id = Actividade.id',
								)
							],
							[
								'table' => 'comprobantes',
								'alias' => 'Comprobante',
								'type' => 'INNER',
//								'fields'=>[
//									'id',
//									'tipodebitoasociado',
//									'tipocreditoasociado',
//									'nombre',
//									'abreviacion',
//									'codigo'],
								'conditions' => array(
									'Comprobante.id = Venta.comprobante_id',
								)
							]
						],
						'conditions' => array(
							'Venta.periodo'=>$periodo,
							'Venta.cliente_id'=>$id
						),
//						'offset'=>$page,
//						'limit'=>1000
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

		unset($cliente['Impcli']);

		$this->set(compact('cliente'));
		$this->set(compact('ventas'));
//		if($this->request->is('ajax')){
			$this->layout = 'ajax';
//		}

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
	 	$this->loadModel('Puntosdeventa');
	 	$this->loadModel('Actividadcliente');
	 	$this->loadModel('Comprobante');
	 	$this->autoRender=false; 
	 	if ($this->request->is('post')) {
	 		$optionsVenta = array( );
	 		//controlar que no se repita la factura para el mismo cluente(subcliente), punto de venta, numero de comprobante y alicuota(si tiene iva)
	 		if($this->request->data['Venta']['tieneMonotributo']){
	 			//Es Monotributista comprobar solo por numero de comprobante
 				$optionsVenta = array(
						'Venta.cliente_id'=>$this->request->data['Venta']['cliente_id'],
						'Venta.comprobante_id'=>$this->request->data['Venta']['comprobante_id'],
 						'Venta.puntosdeventa_id'=>$this->request->data['Venta']['puntosdeventa_id'],
 						'Venta.numerocomprobante'=>$this->request->data['Venta']['numerocomprobante'],
 					);
	 		}else{
	 			//Es Responsable Inscripto comprobar por numero de comprobante y por alicuota
 				$optionsVenta = array(
						'Venta.cliente_id'=>$this->request->data['Venta']['cliente_id'],
						'Venta.comprobante_id'=>$this->request->data['Venta']['comprobante_id'],
 						'Venta.puntosdeventa_id'=>$this->request->data['Venta']['puntosdeventa_id'],
 						'Venta.numerocomprobante'=>$this->request->data['Venta']['numerocomprobante'],
 						'Venta.alicuota'=>$this->request->data['Venta']['alicuota'],
 					);	 			
	 		}
			
			$ventaAnterior = $this->Venta->hasAny($optionsVenta);
			
			if($ventaAnterior){
				$data = array(
		            "respuesta" => "La Venta ".$this->request->data['Venta']['numerocomprobante']." ya ha sido registrada en el periodo".$this->request->data['Venta']['periodo'].". Por favor cambie el numero de comprobante o la alicuota",
		            "venta_id" => 0,
		            "venta"=> array(),		            
		        );
		        $this->layout = 'ajax';
		        $this->set('data', $data);
				$this->render('serializejson');
				return ;
	 		}
			$this->Venta->create();
			if($this->request->data['Venta']['fecha']!="")				
				$this->request->data('Venta.fecha',date('Y-m-d',strtotime(substr($this->request->data['Venta']['periodo'], 3).'-'.substr($this->request->data['Venta']['periodo'], 0,2).'-'.$this->request->data['Venta']['fecha'])));
			if ($this->Venta->save($this->request->data)) {
				$optionsComprobante = array('contain'=>[],'conditions'=>array('Comprobante.id' => $this->request->data['Venta']['comprobante_id']));
				$optionsPuntosDeVenta = array('contain'=>[],'conditions'=>array('Puntosdeventa.id' => $this->request->data['Venta']['puntosdeventa_id']));
				$optionsSubCliente = array('contain'=>[],'conditions'=>array('Subcliente.id'=>$this->request->data['Venta']['subcliente_id']));
				$optionsLocalidade = array('contain'=>[],'conditions'=>array('Localidade.id'=>$this->request->data['Venta']['localidade_id']));
				$optionsActividadCliente = array('contain'=>['Actividade'],'conditions'=>array('Actividadcliente.id'=>$this->request->data['Venta']['actividadcliente_id']));
				$this->request->data['Venta']['fecha'] = date('d',strtotime($this->request->data['Venta']['fecha']));

				$data = array(
		            "respuesta" => "La Venta ha sido creada.",
		            "venta_id" => $this->Venta->getLastInsertID(),
		            "venta"=> $this->request->data,
		            "comprobante"=> $this->Comprobante->find('first',$optionsComprobante),
		            "puntosdeventa"=> $this->Puntosdeventa->find('first',$optionsPuntosDeVenta),
		            "subcliente"=> $this->Subcliente->find('first',$optionsSubCliente),
		            "localidade"=> $this->Localidade->find('first',$optionsLocalidade),
		            "actividadcliente"=> $this->Actividadcliente->find('first',$optionsActividadCliente),
		            "actividadcliente_id"=> $this->request->data['Venta']['actividadcliente_id'],
		            /*AFIP*/
		            "tieneMonotributo"=> $this->request->data['Venta']['tieneMonotributo'],
		            "tieneIVA"=> $this->request->data['Venta']['tieneIVA'],
		            "tieneIVAPercepciones"=> $this->request->data['Venta']['tieneIVAPercepciones'],
		            "tieneImpuestoInterno"=> $this->request->data['Venta']['tieneImpuestoInterno'],
			        /*DGR*/
		            "tieneAgenteDePercepcionIIBB"=> $this->request->data['Venta']['tieneAgenteDePercepcionIIBB'],
			        /*DGRM*/
		            "tieneAgenteDePercepcionActividadesVarias"=> $this->request->data['Venta']['tieneAgenteDePercepcionActividadesVarias'],
		        );
			}
			else{
				$data = array(
		        	"respuesta" => "La Venta NO ha sido creada.Intentar de nuevo mas tarde",
		            "venta_id" => $this->Venta->getLastInsertID()
		        );
			}
			$this->layout = 'ajax';
	        $this->set('data', $data);
			$this->render('serializejson');
			
			}
		}
	
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id ,$tieneMonotributo = null,$tieneIVAPercepciones = null,$tieneImpuestoInterno = null,$tieneIVA = null,$tieneAgenteDePercepcionIIBB = null,$tieneAgenteDePercepcionActividadesVarias = null) {
		$this->loadModel('Subcliente');
		$this->loadModel('Localidade');
		$this->loadModel('Partido');
		$this->loadModel('Actividadcliente');
		$this->loadModel('Puntosdeventa');
		$this->loadModel('Comprobante');
		if (!$this->Venta->exists($id)) {
			throw new NotFoundException(__('Venta No Existe'));
			return;
		}
		$mostrarForm=true;
		$ventaAnterior=false;
		if(!empty($this->data)){ 
			$id = $this->request->data['Venta']['id'];
			//Antes de guardar vamos a revisar que el comprobante o la alicuota cambie y tenemos que controlar que no estemos guardando una venta igual a otra que ya este guardada
			$options = array(
				'contain'=>array( ),
				'conditions' => array('Venta.' . $this->Venta->primaryKey => $id)
				);
			$venta = $this->Venta->find('first', $options);
			
			if(
				$venta['Venta']['comprobante_id'] != $this->request->data['Venta']['comprobante_id']||
				$venta['Venta']['puntosdeventa_id'] != $this->request->data['Venta']['puntosdeventa_id']||
				$venta['Venta']['numerocomprobante'] != $this->request->data['Venta']['numerocomprobante']
			)
			{
				if($this->request->data['Venta']['tieneMonotributo']){
	 			//Es Monotributista comprobar solo por numero de comprobante
 				$optionsVenta = array(
						'Venta.cliente_id'=>$this->request->data['Venta']['cliente_id'],
						'Venta.comprobante_id'=>$this->request->data['Venta']['comprobante_id'],
 						'Venta.puntosdeventa_id'=>$this->request->data['Venta']['puntosdeventa_id'],
 						'Venta.numerocomprobante'=>$this->request->data['Venta']['numerocomprobante'],
 					);
		 		}else{
		 			//Es Responsable Inscripto comprobar por numero de comprobante y por alicuota
	 				$optionsVenta = array(
							'Venta.cliente_id'=>$this->request->data['Venta']['cliente_id'],
							'Venta.comprobante_id'=>$this->request->data['Venta']['comprobante_id'],
	 						'Venta.puntosdeventa_id'=>$this->request->data['Venta']['puntosdeventa_id'],
	 						'Venta.numerocomprobante'=>$this->request->data['Venta']['numerocomprobante'],
	 						'Venta.alicuota'=>$this->request->data['Venta']['alicuota'],
	 					);	 			
		 		}
				$ventaAnterior = $this->Venta->hasAny($optionsVenta);
			}
			if(!$ventaAnterior){
				$this->request->data['Venta']['fecha'] = $this->request->data['Venta']['ventafecha'.$id];
				$this->request->data('Venta.fecha',date(
					'Y-m-d',
					strtotime(
						substr($this->request->data['Venta']['periodo'], 3).'-'.
						substr($this->request->data['Venta']['periodo'], 0,2).'-'.
						$this->request->data['Venta']['fecha']
						)
					)
				);
				if ($this->Venta->save($this->request->data)) {

				} else {

				}
				$options = array(
					'contain'=>array(
						'Actividadcliente'=>array(
				   						'Actividade',
				   					),
						'Puntosdeventa',
						'Subcliente',
						'Localidade'=>array('Partido'),
						'Comprobante',
					),
					'conditions' => array('Venta.' . $this->Venta->primaryKey => $id)
					);
				$venta = $this->Venta->find('first', $options);
//				$venta['Venta']['tieneMonotributo'] = $this->request->data['Venta']['tieneMonotributo'];
//				$venta['Venta']['tieneIVAPercepciones'] = $this->request->data['Venta']['tieneIVAPercepciones'];
//				$venta['Venta']['tieneImpuestoInterno'] = $this->request->data['Venta']['tieneImpuestoInterno'];
//				$venta['Venta']['tieneAgenteDePercepcionActividadesVarias'] = $this->request->data['Venta']['tieneAgenteDePercepcionActividadesVarias'];
//				$venta['Venta']['tieneIVA'] = $this->request->data['Venta']['tieneIVA'];
//				$venta['Venta']['tieneAgenteDePercepcionIIBB'] = $this->request->data['Venta']['tieneAgenteDePercepcionIIBB'];
				$optionsComprobante = array('contain'=>[],'conditions'=>array('Comprobante.id' => $this->request->data['Venta']['comprobante_id']));
				$optionsPuntosDeVenta = array('contain'=>[],'conditions'=>array('Puntosdeventa.id' => $this->request->data['Venta']['puntosdeventa_id']));
				$optionsSubCliente = array('contain'=>[],'conditions'=>array('Subcliente.id'=>$this->request->data['Venta']['subcliente_id']));
				$optionsLocalidade = array('contain'=>[],'conditions'=>array('Localidade.id'=>$this->request->data['Venta']['localidade_id']));
				$optionsActividadCliente = array('contain'=>['Actividade'],'conditions'=>array('Actividadcliente.id'=>$this->request->data['Venta']['actividadcliente_id']));
				$this->request->data['Venta']['fecha'] = date('d',strtotime($this->request->data['Venta']['fecha']));
				$data = array(
					"respuesta" => "La Venta ha sido modificada.",
					"error" => "0",
					"venta_id" => $this->request->data['Venta']['id'],
					"venta"=> $this->request->data,
					"comprobante"=> $this->Comprobante->find('first',$optionsComprobante),
					"puntosdeventa"=> $this->Puntosdeventa->find('first',$optionsPuntosDeVenta),
					"subcliente"=> $this->Subcliente->find('first',$optionsSubCliente),
					"localidade"=> $this->Localidade->find('first',$optionsLocalidade),
					"actividadcliente"=> $this->Actividadcliente->find('first',$optionsActividadCliente),
		            "actividadcliente_id"=> $this->request->data['Venta']['actividadcliente_id'],
					/*AFIP*/
					"tieneMonotributo"=> $this->request->data['Venta']['tieneMonotributo'],
					"tieneIVA"=> $this->request->data['Venta']['tieneIVA'],
					"tieneIVAPercepciones"=> $this->request->data['Venta']['tieneIVAPercepciones'],
					"tieneImpuestoInterno"=> $this->request->data['Venta']['tieneImpuestoInterno'],
					/*DGR*/
					"tieneAgenteDePercepcionIIBB"=> $this->request->data['Venta']['tieneAgenteDePercepcionIIBB'],
					/*DGRM*/
					"tieneAgenteDePercepcionActividadesVarias"=> $this->request->data['Venta']['tieneAgenteDePercepcionActividadesVarias'],
				);
				$this->layout = 'ajax';
				$this->set('data',$data);
				$this->set('mostrarForm',false);
				return;
			}else{
				$data = array('respuesta'=>'La venta'.$this->request->data['Venta']['numerocomprobante']." ya ha sido registrada en el periodo".$this->request->data['Venta']['periodo'].', por favor cambiar comprobante o alicuota');
				$this->set('data',$data);
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
				'Puntosdeventa',
				'Subcliente',
				'Localidade'=>array('Partido'),
				'Comprobante',
				),
			'conditions' => array(
				'Venta.' . $this->Venta->primaryKey => $id)
			);
		$this->request->data = $this->Venta->find('first', $options);
		$this->set('venid',$id);
		
	   	$conditionspuntosdeventa = array('Puntosdeventa.cliente_id' => $this->request->data['Venta']['cliente_id'],);
		$puntosdeventas = $this->Puntosdeventa->find('list',array('conditions' =>$conditionspuntosdeventa));	
		$this->set(compact('puntosdeventas'));

		$conditionsSubClientes = array('Subcliente.cliente_id' => $this->request->data['Venta']['cliente_id'],);
		$subclientes = $this->Subcliente->find('list',array('conditions' =>$conditionsSubClientes));	
		$this->set(compact('subclientes'));

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
		
		$this->set('alicuotas', $this->alicuotas);

		$this->set('condicionesiva', $this->condicionesiva);

		$this->set('tipodebitos', $this->tipodebitos);
		
		$clienteActividadList=$this->Actividadcliente->find('list', array(
												'contain' => array(
													'Actividade'
													),
											   'conditions' => array(
												                'Actividadcliente.cliente_id' =>  $this->request->data['Venta']['cliente_id'], 
												            ),
											   'fields' => array(
													'Actividadcliente.id','Actividade.nombre'
														)
											)
									);	
		$this->set('actividades', $clienteActividadList);
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
		$this->Venta->id = $id;
		if (!$this->Venta->exists()) {
			throw new NotFoundException(__('Invalid Venta'));
		}
		$this->request->onlyAllow('post');
		$data = array();
		if ($this->Venta->delete()) {
			$data['respuesta'] = 'Venta eliminada con exito.';
			$data['error'] = 0;
		} else {
			$data['respuesta'] = 'Venta NO eliminada. Por favor intente mas tarde.';
			$data['error'] = 1;
		}
		$this->layout = 'ajax';
        $this->set('data', $data);
		$this->render('serializejson');
	}
	public function agregarparaimportar()
	{
		$this->autoRender=false;
        $this->loadModel('Puntosdeventa');
        $this->loadModel('Subcliente');

		if (!empty($this->data)) {
			$this->Puntosdeventa->create();
			$this->Subcliente->create();
			$allSaved = true;

			foreach($this->data as $model => $data) {
				switch ($model) {
					case 'Puntosdeventa' :
						/*manipulate ModelName1 data here*/
						if (!$this->Puntosdeventa->saveAll($this->data['Puntosdeventa']))
							$allSaved = false;
						break;
					case 'Subcliente' :
						/*manipulate ModelName2 data here*/
						if (!$this->Subcliente->saveAll($this->data['Subcliente']))
							$allSaved = false;
						break;
				}
			}

			if ($allSaved) {
				/*Print Success Message*/
			}
			if ($allSaved) {
				$this->Session->setFlash(__('Se han guardado los puntos de ventas y los subclientes.'));
				if(count($this->request->data['Subcliente']>0)){
					$this->redirect(
						array('action' => 'importar',
							reset($this->request->data['Subcliente'])['cliente_id'],
								reset($this->request->data['Subcliente'])['periodo'],
							'subcliente')
					);
				}else if (count($this->request->data['Puntosdeventa']>0)){
					$this->redirect(
						array(
							'action' => 'importar',
							reset($this->request->data['Puntosdeventa'])['cliente_id'],
							reset($this->request->data['Puntosdeventa'])['periodo']),
							'puntodeventa'
					);
				}
			} else {
				$this->Session->setFlash(__('Error al guardar.'));
				$this->log(json_encode($this->request->data));
				$this->log(json_encode($this->Puntosdeventa->validationErrors));
				$this->log(json_encode($this->Puntosdeventa->invalidFields()));
				$this->log(json_encode($this->Puntosdeventa->getDataSource()->getLog(true, true)));

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
		set_time_limit (360);
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		$this->loadModel('Subcliente');
		$this->loadModel('Localidade');
		$this->loadModel('Partido');
		$this->loadModel('Actividadcliente');
		$this->loadModel('Puntosdeventa');
		$this->loadModel('Comprobante');
		$this->loadModel('Domicilio');
		$this->loadModel('Cliente');

		$folderVentas = WWW_ROOT.'files'.DS.'ventas'.DS.$cliid.DS.$periodo.DS.'ventas';
		$folderAlicuotas = WWW_ROOT.'files'.DS.'ventas'.DS.$cliid.DS.$periodo.DS.'alicuotas';
		if ($this->request->is('post')) {
			$folderVentas = WWW_ROOT.'files'.DS.'ventas'.DS.$this->request->data['Venta']['cliid'].DS.$this->request->data['Venta']['periodo'].DS.'ventas';
			$folderAlicuotas = WWW_ROOT.'files'.DS.'ventas'.DS.$this->request->data['Venta']['cliid'].DS.$this->request->data['Venta']['periodo'].DS.'alicuotas';
		  	$fileNameVenta = null;
			$tmpNameVenta= $this->request->data['Venta']['archivoventa']['tmp_name'];
			if (!empty($tmpNameVenta)&& is_uploaded_file($tmpNameVenta)) {
			    // Strip path information
			    $fileNameVenta = $this->request->data['Venta']['archivoventa']['name'];
	          	move_uploaded_file($tmpNameVenta, $folderVentas.DS.$fileNameVenta);
				//chmod($folderVentas.DS.$fileNameVenta, 0777);
			}
			$fileAlicuota = null;
			$tmpNameAlicuota= $this->request->data['Venta']['archivoalicuota']['tmp_name'];
			if (!empty($tmpNameAlicuota)&& is_uploaded_file($tmpNameAlicuota)) {
			    // Strip path information
			    $fileAlicuota = $this->request->data['Venta']['archivoalicuota']['name'];
	          	move_uploaded_file($tmpNameAlicuota, $folderAlicuotas.DS.$fileAlicuota);
				//chmod($folderVentas.DS.$tmpNameAlicuota, 0777);
			}
		}
		//Puntos de Venta
			$conditionspuntosdeventa = array('Puntosdeventa.cliente_id' => $cliid,);
			$puntosdeventas = $this->Puntosdeventa->find('list',array('conditions' =>$conditionspuntosdeventa));
			$containpuntosdeventa = array('Domicilio');
			$fieldspuntosdeventa = array('Puntosdeventa.nombre','Domicilio.localidade_id',);
			$puntosdeventasdomicilio = $this->Puntosdeventa->find('list',array(
				'contain' =>$containpuntosdeventa,
				'conditions' =>$conditionspuntosdeventa,
				'fields'=>$fieldspuntosdeventa)
			);
		//Subclientes
		$conditionsSubClientes = array('Subcliente.cliente_id' => $cliid,);
		$subclientes = $this->Subcliente->find('list',array('conditions' =>$conditionsSubClientes));
		//Localidades
		$conditionsLocalidades = array(
			'contain'=>'Partido',
			'fields'=>array('Localidade.id','Localidade.nombre','Partido.nombre'),
			'order'=>array('Partido.nombre','Localidade.nombre')
		);
		$localidades = $this->Localidade->find('list',$conditionsLocalidades);
		//Partidos
		$partidos = $this->Partido->find('list');
		//Comprobantes
		$comprobantes = $this->Comprobante->find('list');
		$supercomprobantes = $this->Comprobante->find('all',array('contain'=>array()));
		$this->set(compact('comprobantes', 'supercomprobantes', 'partidos', 'localidades','puntosdeventasdomicilio','puntosdeventas','subclientes'));
		//Alicuotas
		$this->set('alicuotas', $this->alicuotascodigo);
		//Condicion IVA
		$this->set('condicionesiva',$this->condicionesiva);
		//Tipo Debito
		$this->set('tipodebitos', $this->tipodebitos);
		//Actividades del Cliente
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
		//Opciones del sistema de Facturacion de los puntos de venta
		$this->set('optionSisFact', $this->optionSisFact);
		//Domicilios
		$optionsDoc = array(
			'conditions' => array('Domicilio.cliente_id'=>$cliid ),
		);
		$domicilios = $this->Domicilio->find('list',$optionsDoc);
		$this->set('domicilios', $domicilios);

		$this->set(compact('cliid','periodo','folderVentas','folderAlicuotas'));

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

		//en esta seccion vamos a listar las ventas por punto de venta y por tipo de comprobante
		$timePeriodo = strtotime("01-".$periodo ." -1 months");
		$periodoPrevio = date("m-Y",$timePeriodo);
		$optionsventas=array(
			'contain'=>array(
				'Comprobante',
				'Puntosdeventa'
			),
			'fields'=>array(
				'MAX(numerocomprobante) as maxnumerocomprobante'
			),
			'group'=>array('Venta.puntosdeventa_id','Venta.comprobante_id'),
			'conditions'=>array(
				'Venta.periodo'=>$periodoPrevio,
				'Venta.cliente_id'=>$cliid
			)
		);
		$ultimasventas = $this->Venta->find('all',$optionsventas);
		$this->set('ultimasventas',$ultimasventas);

		$this->Venta->virtualFields = array(
			'fullid' => "CONCAT(Venta.comprobante_id, '-' ,Venta.numerocomprobante, '-', Venta.puntosdeventa_id, '-', Venta.alicuota)"
		);
		$igualTipoComprobante=false;
		$igualPuntoDV=false;
		$igualAlicuota=false;
		$igualNumeroComprobante=false;

		$optionsventasdelperiodo=array(
			'contain'=>array(
			),
			'fields'=>array(
				'*','fullid'
			),
			'conditions'=>array(
				'Venta.periodo'=>$periodo,
				'Venta.cliente_id'=>$cliid
			)
		);
		$ventasperiodo = $this->Venta->find('all',$optionsventasdelperiodo);
		$this->set('ventasperiodo',$ventasperiodo);
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
	public function exportartxt($cliid=null,$periodo=null){
		$this->loadModel('Cliente');
		$pemes = substr($periodo, 0, 2);
		$peanio = substr($periodo, 3);

		$cliente=$this->Cliente->find('first', [
                'contain'=>[
				],
				'conditions' => [
					'id' => $cliid,
                 ],
			]
        );

        $conditionsVentas = [
                'fields'=>[
                    'Venta.*','Count(*) as cantalicuotas','SUM(Venta.total) as totalfactura'
                ],
                'group'=>[
					'Venta.puntosdeventa_id',
					'Venta.numerocomprobante',
					'Venta.comprobante_id'
				],
                'contain'=>[
                    'Puntosdeventa'=>[
                        'fields'=>['id','nombre']
                    ],
                    'Subcliente'=>[
                        'fields'=>['id','nombre','cuit']
                    ],
                    'Comprobante'=>[
                        'fields'=>['id','tipodebitoasociado','tipocreditoasociado','nombre','codigo']
                    ],
                ],
                'conditions' => [
                    'Venta.periodo'=>$periodo,
                    'Venta.cliente_id'=>$cliid
                ],
        ];
        $conditionsAlicuotas = [
                'Puntosdeventa'=>[
                    'fields'=>['id','nombre']
                ],
                'Subcliente'=>[
                    'fields'=>['id','nombre','cuit']
                ],
                'Localidade'=>[
                    'Partido',
                    'fields'=>['id','nombre']
                ],
                'Comprobante'=>[
                    'fields'=>['id','tipodebitoasociado','tipocreditoasociado','nombre','codigo']
                ],
                'conditions' => [
                    'Venta.periodo'=>$periodo,
                    'Venta.cliente_id'=>$cliid
                ],
        ];
        $ventas=$this->Venta->find('all', $conditionsVentas);
        $alicuotas=$this->Venta->find('all', $conditionsAlicuotas);
		$alicuotascodigoreverse = $this->alicuotascodigoreverse;

		$this->set(compact('ventas','alicuotas','cliente','cliid','periodo','alicuotascodigoreverse'));

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
