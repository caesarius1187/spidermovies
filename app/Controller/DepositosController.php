<?php
App::uses('AppController', 'Controller');
/**
 * Depositos Controller
 *
 * @property Deposito $Deposito
 * @property PaginatorComponent $Paginator
 */
class DepositosController extends AppController {

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
		$this->Deposito->recursive = 0;
		$this->set('depositos', $this->Paginator->paginate());
	}
	public function getdepositos($periodo,$cliid) {
		$this->loadModel('Honorario');
		$this->loadModel('Cliente');
		$this->loadModel('Grupocliente');
		$options = array(
			'conditions' => array(
				'Deposito.cliente_id' => $cliid,
				'Deposito.periodo' => $periodo

				)
		);
		$depositos = $this->Deposito->find('all', $options);
		$this->set('depositos', $depositos);

		$optionsGCLI = array(
			'conditions' => array(
				'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
				),
			'contain'=>array()
			);
		$GrupoClientes = $this->Grupocliente->find('list', $optionsGCLI);
		
		$options = array(
			'contain'=>array(
				'Cliente'=>array(

					)
				),
			'conditions' => array(
				'Cliente.grupocliente_id' => $GrupoClientes,		
				),
			'fields'=> array(
				'MAX(Deposito.numero) as depomax'
				),
			'group' => 'Deposito.numero'
		);
		$maxdeposito = $this->Deposito->find('all', $options);
		$this->set('maxdeposito', $maxdeposito);

		$optionsHonorario = array(
			'conditions' => array(
				'Honorario.cliente_id' => $cliid,
				'Honorario.periodo' => $periodo
				)
		);
		$honorario = $this->Honorario->find('first', $optionsHonorario);
		$this->set('honorario', $honorario);
		$this->request->data = $honorario;
		
		$this->set('periodo', $periodo);
		$this->set('cliid', $cliid);

		$cliente = $this->Cliente->find('first',array(
										'conditions' => array(
								 			'Cliente.id' => $cliid,								 		
								 			),	
										'fields'=>array('Cliente.grupocliente_id','Cliente.honorario')
										)
									);
		$this->set('gcliid', $cliente['Cliente']['grupocliente_id']);
		$this->set('honorariocliente', $cliente['Cliente']['honorario']);
		$this->set('cliid', $cliente['Cliente']['id']);

		$this->layout = 'ajax';
		$this->render('getdepositos');
	}	
	public function cargar($periodo,$cliid) {
		$this->loadModel('Cliente');
		$this->loadModel('Grupocliente');
		$options = array(
			'contain'=>[],
			'conditions' => [
				'Deposito.cliente_id' => $cliid,
				'Deposito.periodo' => $periodo
				]
		);
		$depositos = $this->Deposito->find('all', $options);
		$this->set('depositos', $depositos);

		$optionsGCLI = array(
			'conditions' => array(
				'Cliente.id' => $cliid,
				),
			'contain'=>[]
			);
		$Cliente = $this->Cliente->find('first', $optionsGCLI);

		$options = array(
			'joins'=>[
				['table'=>'clientes',
					'alias' => 'Cliente',
					'type'=>'inner',
					'conditions'=> [
						'Cliente.grupocliente_id' => $Cliente['Cliente']['grupocliente_id'],
					]
				],
			],
			'contain'=>[],
			'conditions' => array(
				),
			'fields'=> array(
				'MAX(Deposito.numero*1) as depomax',
				),
			'group' => 'Cliente.grupocliente_id'
		);
		$maxdeposito = $this->Deposito->find('all', $options);
		$this->set('maxdeposito', $maxdeposito);

		$this->set('periodo', $periodo);
		$this->set('cliid', $cliid);

		$cliente = $this->Cliente->find('first',array(
										'contain'=>[],
										'conditions' => array(
								 			'Cliente.id' => $cliid,
								 			),
										'fields'=>array('Cliente.id','Cliente.grupocliente_id','Cliente.honorario')
										)
									);
		$this->set('gcliid', $cliente['Cliente']['grupocliente_id']);
		$this->set('honorariocliente', $cliente['Cliente']['honorario']);
		$this->set('cliid', $cliente['Cliente']['id']);

		$this->layout = 'ajax';
		$this->render('cargar');
	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Deposito->exists($id)) {
			throw new NotFoundException(__('Invalid deposito'));
		}
		//$this->layout="ajax";
		$this->loadModel('Estudio');
		$this->loadModel('Domicilio');
		$this->loadModel('Puntosdeventa');
		$estudio = $this->Estudio->find('first',array(
										'conditions' => array(
								 			'Estudio.id' => $this->Session->read('Auth.User.estudio_id'),								 		
								 			),	
										)
									);
		$this->set('estudio', $estudio);

		$options = array(
			'contain'=>array(
				'Cliente',
				),
			'conditions' => array(
				'Deposito.' . $this->Deposito->primaryKey => $id
				)
		);
		$depositos = $this->Deposito->find('first', $options);
		$this->set('deposito', $depositos);

		$domicilios = $this->Domicilio->find('list',array(
										'fields' => array('Domicilio.id', 'Domicilio.calle','Localidade.nombre'),
										'conditions' => array(
								 			'Domicilio.cliente_id' => $depositos['Deposito']['cliente_id'],								 		
								 			),	
										'contain' => array('Localidade',
											),
										)
									);
		$this->set('domicilios', $domicilios);

		$puntosdeventas = $this->Puntosdeventa->find('list',array(
										'fields' => array('Puntosdeventa.id', 'Puntosdeventa.nombre'),
										'conditions' => array(
								 			'Puntosdeventa.cliente_id' => $depositos['Deposito']['cliente_id'],								 		
								 			),	
										)
									);
		$this->set('puntosdeventas', $puntosdeventas);
		
	}
/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Eventoscliente');
		$resp ="";
		$this->autoRender=false; 
		if ($this->request->is('post')) {

			$this->request->data('Deposito.fecha',date('Y-m-d',strtotime($this->request->data['Deposito']['fecha'])));				

			$this->Deposito->create();
			$id = 0;
			if ($this->Deposito->save($this->request->data)) {
					$id = $this->Deposito->getLastInsertID();
					$options = array(
						'conditions' => array(
							'Deposito.' . $this->Deposito->primaryKey => $id
							)
						);
					$createdDepo = $this->Deposito->find('first', $options);	
					$this->set('deposito',$createdDepo);
					$this->set('evento',$this->request->data['Deposito']['evento_id']);

					//Vamos a guardar esto en el evento cliente del periodo correspondiente
					$eventoId=0;
					$options = array(
						'conditions' => array(
							'Eventoscliente.cliente_id'=> $this->request->data['Deposito']['cliente_id'],
							'Eventoscliente.periodo'=>$this->request->data['Deposito']['periodo']
						)
					);
					$eventocliente = $this->Eventoscliente->find('all', $options);
					if(count($eventocliente)!=0){
						$eventoId=$eventocliente[0]['Eventoscliente']['id'];
					}
					if ($eventoId!=0) {
						$this->Eventoscliente->read(null, $eventoId);
						$this->Eventoscliente->set('reciboscargados',1);
						if ($this->Eventoscliente->save()) {
						} else {
							$this->set('error',1);
						}
					}else{
						$this->Eventoscliente->create();
						$this->Eventoscliente->set('cliente_id',$this->request->data['Deposito']['cliente_id']);
						$this->Eventoscliente->set('periodo',$this->request->data['Deposito']['periodo']);
						$this->Eventoscliente->set('reciboscargados',1);
						$this->Eventoscliente->set('user_id',$this->Session->read('Auth.User.estudio_id'));
						if($this->Eventoscliente->save()){

						}else{
							$this->set('error',1);
						}
					}

					$this->autoRender=false; 		
					$this->layout = 'ajax';
					$this->render('add');		
					return;									
				}
				
		}	else {
			$this->set('respuesta','Error: NO se creo el deposito. Intente de nuevo. (500)');	
			$this->autoRender=false; 
			$this->layout = 'ajax';
			$this->render('add');
			return;
		}		
	}
	public function addajax($cliid = null,$fecha = null,$monto= null,$periodo= null,$desc= null) {
	 	$this->request->onlyAllow('ajax');

		$this->Deposito->create();
		$this->Deposito->set('cliente_id',$cliid);
		$this->Deposito->set('fecha',date('Y-m-d',strtotime($fecha)));
		$this->Deposito->set('monto',$monto);
		$this->Deposito->set('periodo',$periodo);
		$this->Deposito->set('descripcion',$desc);
		if ($this->Deposito->save($this->request->data)) {
			$this->set('respuesta','El Deposito ha sido creado.');	
			$this->set('deposito_id',$this->Deposito->getLastInsertID());
		}
		else{

		}
		$this->layout = 'ajax';
		$this->render('addajax');
	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Deposito->exists($id)) {
			throw new NotFoundException(__('Invalid deposito'));
		}
		if ($this->request->is('post')) {
			$depoid=$this->request->data['Deposito']['id'];
			$this->request->data('Deposito.fecha',date('Y-m-d',strtotime($this->request->data['Deposito']['fecha'.$depoid])));
			$this->request->data['Deposito']['periodo'] = $this->request->data['Deposito']['mesdesde'].'-'.$this->request->data['Deposito']['aniodesde'];
			if ($this->Deposito->save($this->request->data)) {
				//dont answer anithing bc theres just ajax call to save
				$this->set('showTheForm',false);
				$this->layout = 'ajax';
				if(!empty($this->data)){ 
					echo 'Deposito Modificado'; 
				}else{ 
					echo 'Deposito No Modificado'; 
				} 
				return ;
			}else{

			} 
				//$this->redirect(array('controller'=>'clientes','action' => 'view',$this->request->data['Deposito']['cliente_id']));		
		} else {

		}
		$options = array('conditions' => array('Deposito.' . $this->Deposito->primaryKey => $id));
		$this->request->data = $this->Deposito->find('first', $options);
		$clientes = $this->Deposito->Cliente->find('list');
		$this->set(compact('clientes','myFormReturn'));
		

		
	}
	public function editajax($id=null) {

	 	//$this->request->onlyAllow('ajax');
		if (!$this->Deposito->exists($id)) {
			throw new NotFoundException(__('Deposito Invalido'));
		}
		
		$options = array('conditions' => array('Deposito.' . $this->Deposito->primaryKey => $id));
		$this->request->data = $this->Deposito->find('first', $options);

		$optionsCli = array('conditions' => array('Cliente.id' => $this->request->data['Deposito']['cliente_id']));
		$clientes = $this->Deposito->Cliente->find('list', $optionsCli);
	
		$this->set(compact('clientes'));
		$this->set('showTheForm',$this->request->is('post'));

		$this->layout = 'ajax';
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
		$this->Deposito->id = $id;
		if (!$this->Deposito->exists()) {
			throw new NotFoundException(__(' Deposito Invalido'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Deposito->delete()) {
			$this->set("data","El Deposito ha sido eliminado");
		} else {
			$this->set("data","El Deposito NO a sido eliminado");			
		}

		$this->layout = 'ajax';
		$this->render('serializejson');
	}
//	public function numerardepositos(){
//
//		ini_set('max_execution_time', 600);
//		$optionsDepositos = array(
//			'contain'=>[],
//			'conditions' => array(
//			),
//			'order'=>[
//				'Deposito.cliente_id',
//				'Deposito.id'
//			]
//		);
//		$depositos = $this->Deposito->find('all', $optionsDepositos);
//		$numeroDeposito = 1;
//		$clienteActual = $depositos[0]['Deposito']['cliente_id'];
//		$respuesta = [];
//		foreach ($depositos as $deposito) {
//			$clientenuevo = $deposito['Deposito']['cliente_id'];
//			if($clienteActual!=$clientenuevo){
//				$numeroDeposito=1;
//				$clienteActual = $clientenuevo;
//			}
//			$this->Deposito->read(null, $deposito['Deposito']['id']);
//			$this->Deposito->set('numero',$numeroDeposito);
//			if(!isset($respuesta[$deposito['Deposito']['cliente_id']]))
//				$respuesta[$deposito['Deposito']['cliente_id']]=[];
//			if($this->Deposito->save()){
//				$respuesta[$deposito['Deposito']['cliente_id']][]=$deposito['Deposito']['id']."_".$numeroDeposito;
//			}else{
//				$respuesta[$deposito['Deposito']['cliente_id']][]=$deposito['Deposito']['id']."_".$numeroDeposito."ERROR";
//			}
//			$numeroDeposito ++;
//		}
//		$this->set('data',$respuesta);
//		$this->autoRender=false;
//		$this->layout = 'ajax';
//		$this->render('serializejson');
//	}
}