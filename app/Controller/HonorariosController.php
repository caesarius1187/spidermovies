<?php
App::uses('AppController', 'Controller');
/**
 * Honorarios Controller
 *
 * @property Honorario $Honorario
 * @property PaginatorComponent $Paginator
 */
class HonorariosController extends AppController {

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
		$this->Honorario->recursive = 0;
		$this->set('honorarios', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Honorario->exists($id)) {
			throw new NotFoundException(__('Invalid honorario'));
		}
		$options = array('conditions' => array('Honorario.' . $this->Honorario->primaryKey => $id));
		$this->set('honorario', $this->Honorario->find('first', $options));
	}
	public function cargar($periodo,$cliid) {
		$this->loadModel('Cliente');
		$this->loadModel('Grupocliente');


		$optionsGCLI = array(
			'conditions' => array(
				'Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),
			),
			'contain'=>array()
		);
		$GrupoClientes = $this->Grupocliente->find('list', $optionsGCLI);

		$optionsHonorario = array(
			'contain'=>[],
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
				'contain'=>[],
				'conditions' => array(
					'Cliente.id' => $cliid,
				),
				'fields'=>array('Cliente.grupocliente_id','Cliente.honorario')
			)
		);
		$this->set('gcliid', $cliente['Cliente']['grupocliente_id']);
		$this->set('honorariocliente', $cliente['Cliente']['honorario']);
		$this->set('cliid', $cliid);

		$this->layout = 'ajax';
		$this->render('cargar');
	}
	public function add() {
		$this->loadModel('Eventoscliente');
		$resp ="";
		$this->autoRender=false; 
		if ($this->request->is('post')) {

			$this->request->data('Honorario.fecha',date('Y-m-d',strtotime($this->request->data['Honorario']['fecha'])));				

			$this->Honorario->create();
			$id = 0;
			$options = array(
					'conditions' => array(
						'Honorario.id'=> $this->request->data['Honorario']['id'],
						)
					);
			$createdHono = $this->Honorario->find('first', $options);
			$honoCreado= false;
			
			if(count($createdHono)>0){
				//el impcli ya esta creado por lo que ahora resta buscar los periodos activos y ver si se puede crear uno
				$honoCreado= true;
				$this->set('honoCreado','Error1: El honorario ya esta relacionado.');	
				$id = $createdHono['Honorario']['id'];
			}else{
				unset($this->request->data['Honorario']['id']);

			}
			if ($this->Honorario->save($this->request->data)) {
					if(!$honoCreado){
						$id = $this->Honorario->getLastInsertID();
					}
					$options = array(
						'conditions' => array(
							'Honorario.' . $this->Honorario->primaryKey => $id
							)
						);
					$createdHono = $this->Honorario->find('first', $options);	
					$this->set('honorario',$createdHono);

					//Vamos a guardar esto en el evento cliente del periodo correspondiente
					$eventoId=0;
					$options = array(
						'conditions' => array(
							'Eventoscliente.cliente_id'=> $this->request->data['Honorario']['cliente_id'],
							'Eventoscliente.periodo'=>$this->request->data['Honorario']['periodo']
						)
					);
					$eventocliente = $this->Eventoscliente->find('all', $options);
					if(count($eventocliente)!=0){
						$eventoId=$eventocliente[0]['Eventoscliente']['id'];
					}
					if ($eventoId!=0) {
						$this->Eventoscliente->read(null, $eventoId);
						$this->Eventoscliente->set('honorarioscargador',1);
						if ($this->Eventoscliente->save()) {
						} else {
							$this->set('error',1);
						}
					}else{
						$this->Eventoscliente->create();
						$this->Eventoscliente->set('cliente_id',$this->request->data['Honorario']['cliente_id']);
						$this->Eventoscliente->set('periodo',$this->request->data['Honorario']['periodo']);
						$this->Eventoscliente->set('honorarioscargador',1);
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
				else {
					$this->set('respuesta','Error: NO se creo el honorario. Intente de nuevo.');	
					$this->autoRender=false; 
					$this->layout = 'ajax';
					$this->render('add');
					return;
				}
		}	else {
			$this->set('respuesta','Error: NO se creo el honorario. Intente de nuevo. (500)');	
			$this->autoRender=false; 
			$this->layout = 'ajax';
			$this->render('add');
			return;
		}		
	}
	public function addajax($cliid = null,$fecha = null,$monto= null,$periodo= null,$desc= null) {
	 	$this->request->onlyAllow('ajax');

		$this->Honorario->create();
		$this->Honorario->set('cliente_id',$cliid);

		$this->Honorario->set('fecha',date('Y-m-d',strtotime($fecha)));
		
		$this->Honorario->set('monto',$monto);
		$this->Honorario->set('periodo',$periodo);
		$this->Honorario->set('descripcion',$desc);
		$this->Honorario->set('estado','no pagado');

		if ($this->Honorario->save($this->request->data)) {
			$this->set('respuesta','El Honorario ha sido creado.');	
			$this->set('honorario_id',$this->Honorario->getLastInsertID());

		}
		else{

		}
		$this->layout = 'ajax';
		$this->render('addajax');
	}
	public function edit($id = null) {
		if (!$this->Honorario->exists($id)) {
			throw new NotFoundException(__('Invalid honorario'));
		}
		if ($this->request->is('post')) {
			$honoid=$this->request->data['Honorario']['id'];
			$this->request->data['Honorario']['fecha'] =date('Y-m-d',strtotime($this->request->data['Honorario']['fecha'.$honoid]));
			$this->request->data['Honorario']['periodo'] = $this->request->data['Honorario']['mesdesde'].'-'.$this->request->data['Honorario']['aniodesde'];

			if ($this->Honorario->save($this->request->data)) {
				//dont answer anithing bc theres just ajax call to save
				$this->set('showTheForm',false);
				$this->layout = 'ajax';
				if(!empty($this->data)){ 
					echo 'Honorario Modificado'; 
				}else{ 
					echo 'Honorario No Modificado'; 
				} 
				return ;
			}else{

			} 
		} else {
			
		}
		$options = array('conditions' => array('Honorario.' . $this->Honorario->primaryKey => $id));
		$this->request->data = $this->Honorario->find('first', $options);
		$clientes = $this->Honorario->Cliente->find('list');
		$this->set(compact('clientes'));
	}
	public function editajax($id=null) {

	 	//$this->request->onlyAllow('ajax');
		if (!$this->Honorario->exists($id)) {
			throw new NotFoundException(__('Honorario Invalido'));
		}
		
		$options = array('conditions' => array('Honorario.' . $this->Honorario->primaryKey => $id));
		$this->request->data = $this->Honorario->find('first', $options);

		$optionsCli = array('conditions' => array('Cliente.id' => $this->request->data['Honorario']['cliente_id']));
		$clientes = $this->Honorario->Cliente->find('list', $optionsCli);
	
		$this->set(compact('clientes'));
		$this->set('showTheForm',$this->request->is('post'));

		$this->layout = 'ajax';
		$this->render('edit');	
	}
	public function delete($id = null) {
		$this->Honorario->id = $id;
		if (!$this->Honorario->exists()) {
			throw new NotFoundException(__('Invalid honorario'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Honorario->delete()) {
			$this->Session->setFlash(__('The honorario has been deleted.'));
		} else {
			$this->Session->setFlash(__('The honorario could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
