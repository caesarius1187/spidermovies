<?php
App::uses('AppController', 'Controller');
/**
 * Eventosclientes Controller
 *
 * @property Eventoscliente $Eventoscliente
 * @property PaginatorComponent $Paginator
 */
class EventosclientesController extends AppController {

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
		$this->Eventoscliente->recursive = 0;
		$this->set('eventosclientes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Eventoscliente->exists($id)) {
			throw new NotFoundException(__('Invalid eventoscliente'));
		}
		$options = array('conditions' => array('Eventoscliente.' . $this->Eventoscliente->primaryKey => $id));
		$this->set('eventoscliente', $this->Eventoscliente->find('first', $options));
	}

	public function ver($eventoId = null,$tarea = null,$cliid) {
		

		if (!$this->Eventoscliente->exists($eventoId)) {
			$this->set('error','La tarea no existe.');
		}
		$options = array('conditions' => array('Eventoscliente.' . $this->Eventoscliente->primaryKey => $eventoId));
		$this->request->data = $this->Eventoscliente->find('first', $options);	

		
		$this->set('tarea', $tarea);
		$this->set('cliid', $cliid);

		$this->layout = 'ajax';
		$this->render('ver');
	}
/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Eventoscliente->create();
			if ($this->Eventoscliente->save($this->request->data)) {
				$this->Session->setFlash(__('The eventoscliente has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The eventoscliente could not be saved. Please, try again.'));
			}
		}
		$clientes = $this->Eventoscliente->Cliente->find('list');
		$users = $this->Eventoscliente->User->find('list');
		$this->set(compact('clientes', 'users'));
	}
	public function realizareventocliente($id = null,$tarea = null,$periodo= null,$cliente= null,$estadoTarea=null) {
	 	//$this->request->onlyAllow('ajax');
		//Configure::write('debug', 2);
		//Debemos chekiar que tampoco exista para un periodo y cliente definido
		$eventoId=0;
	 	$options = array(
					'conditions' => array(
						'Eventoscliente.cliente_id'=> $cliente,
						'Eventoscliente.periodo'=>$periodo
						)
					);
		$eventocliente = $this->Eventoscliente->find('all', $options);
		if(count($eventocliente)!=0){
			$eventoId=$eventocliente[0]['Eventoscliente']['id'];
		}
		if ($eventoId!=0) {
			$this->Eventoscliente->read(null, $eventoId);
			$this->Eventoscliente->set($tarea,$estadoTarea);
			if ($this->Eventoscliente->save()) {
				$this->set('error',0);
				$this->set('respuesta','La tarea ha sido realizada.2');	
				$this->set('evento_id',$this->Eventoscliente->getLastInsertID());
			} else {
				$this->set('error',1);
				$this->set('respuesta','La tarea NO ha sido realizada.');	
			}					
		}else{
			$this->Eventoscliente->create();
			$this->Eventoscliente->set('cliente_id',$cliente);
			$this->Eventoscliente->set('periodo',$periodo);
			$this->Eventoscliente->set($tarea,$estadoTarea);
			$this->Eventoscliente->set('user_id',$this->Session->read('Auth.User.estudio_id'));
			if($this->Eventoscliente->save()){
				$this->set('error',0);
				$this->set('respuesta','La tarea ha sido realizada.');	
				$this->set('evento_id',$this->Eventoscliente->getLastInsertID());
			}else{
				$this->set('error',1);
				$this->set('respuesta','La tarea NO ha sido realizada.');	
			}
		}
		$this->layout = 'ajax';
		$this->render('realizareventocliente');
	}
	public function tareasolicitar() {
		/*
		vamos a buscar siempre el evento con cliente_id y periodo, solo se puede crear una sola vez,
		si existe el evento editamos los datos de los 6 campos y guardamos, y si no lo creamos
		*/
		
		$eventoId = 0;
	 	$options = array(
					'conditions' => array(
						'Eventoscliente.cliente_id'=> $this->request->data['Eventoscliente']['cliente_id'],
						'Eventoscliente.periodo'=>$this->request->data['Eventoscliente']['periodo']
						)
					);
		$eventocliente = $this->Eventoscliente->find('all', $options);
		if(count($eventocliente)!=0){
			$eventoId=$eventocliente[0]['Eventoscliente']['id'];
		}
		$data=array();
		if ($eventoId!=0) {
			$this->Eventoscliente->read(null, $eventoId);
			if (array_key_exists('fccompra', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('fccompra',$this->request->data['Eventoscliente']['fccompra']);			    
			}
			if (array_key_exists('libroivaventas', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('libroivaventas',$this->request->data['Eventoscliente']['libroivaventas']);
			}
			if (array_key_exists('fcventa', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('fcventa',$this->request->data['Eventoscliente']['fcventa']);
			}
			if (array_key_exists('descargawebafip', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('descargawebafip',$this->request->data['Eventoscliente']['descargawebafip']);
			}
			if (array_key_exists('banco', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('banco',$this->request->data['Eventoscliente']['banco']);
			}
			if (array_key_exists('tarjetadecredito', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('tarjetadecredito',$this->request->data['Eventoscliente']['tarjetadecredito']);
			}
			if (array_key_exists('fcluz', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('fcluz',$this->request->data['Eventoscliente']['fcluz']);
			}
			if (array_key_exists('sueldos', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('sueldos',$this->request->data['Eventoscliente']['sueldos']);
			}
			if (array_key_exists('librounico', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('librounico',$this->request->data['Eventoscliente']['librounico']);
			}
			if ($this->Eventoscliente->save()) {
				$data['respuesta']='La tarea ha sido realizada.1';
			} else {
				$data['error']='1';
				$data['respuesta']='La tarea NO ha sido realizada.1';
			}					
		}else{
			$this->Eventoscliente->create();
			$this->Eventoscliente->set('cliente_id',$this->request->data['Eventoscliente']['cliente_id']);
			$this->Eventoscliente->set('periodo',$this->request->data['Eventoscliente']['periodo']);
			if (array_key_exists('fccompra', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('fccompra',$this->request->data['Eventoscliente']['fccompra']);
			}
			if (array_key_exists('libroivaventas', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('libroivaventas',$this->request->data['Eventoscliente']['libroivaventas']);
			}
			if (array_key_exists('fcventa', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('fcventa',$this->request->data['Eventoscliente']['fcventa']);
			}
			if (array_key_exists('descargawebafip', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('descargawebafip',$this->request->data['Eventoscliente']['descargawebafip']);
			}
			if (array_key_exists('banco', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('banco',$this->request->data['Eventoscliente']['banco']);
			}
			if (array_key_exists('tarjetadecredito', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('tarjetadecredito',$this->request->data['Eventoscliente']['tarjetadecredito']);
			}
			if (array_key_exists('fcluz', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('fcluz',$this->request->data['Eventoscliente']['fcluz']);
			}
			if (array_key_exists('sueldos', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('sueldos',$this->request->data['Eventoscliente']['sueldos']);
			}
			if (array_key_exists('librounico', $this->request->data['Eventoscliente'])) {
				$this->Eventoscliente->set('librounico',$this->request->data['Eventoscliente']['librounico']);
			}			
			if($this->Eventoscliente->save()){
				$data['respuesta']='La tarea ha sido realizada.2';
			}else{
				$data['error']='2';
				$data['respuesta']='La tarea NO ha sido realizada.2';
			}
		}
		$this->set('data',$data);
		$this->layout = 'ajax';
		$this->render('serializejson');
	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Eventoscliente->exists($id)) {
			throw new NotFoundException(__('Invalid eventoscliente'));
		}
		if ($this->request->is('post')) {
			if ($this->Eventoscliente->save($this->request->data)) {
				$this->Session->setFlash(__('La tarea a sido guardada'));
				return $this->redirect(array('controller'=>'clientes','action' => 'avance',$this->request->data['Eventoscliente']['cliid']));
			} else {
				$this->Session->setFlash(__('La tarea NO a sido guardada. Por favor intente mas tarde.'));
				return $this->redirect(array('controller'=>'clientes','action' => 'avance',$this->request->data['Eventoscliente']['cliid']));
			}
		} else {
			$options = array('conditions' => array('Eventoscliente.' . $this->Eventoscliente->primaryKey => $id));
			$this->request->data = $this->Eventoscliente->find('first', $options);
		}
		return $this->redirect(array('controller'=>'clientes','action' => 'index',$this->request->data['Eventoscliente']['cliid']));
		$clientes = $this->Eventoscliente->Cliente->find('list');
		$users = $this->Eventoscliente->User->find('list');
		$this->set(compact('clientes', 'users'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Eventoscliente->id = $id;
		if (!$this->Eventoscliente->exists()) {
			throw new NotFoundException(__('Invalid eventoscliente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Eventoscliente->delete()) {
			$this->Session->setFlash(__('The eventoscliente has been deleted.'));
		} else {
			$this->Session->setFlash(__('The eventoscliente could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
