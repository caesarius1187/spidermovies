<?php
App::uses('AppController', 'Controller');
/**
 * Estudios Controller
 *
 * @property Estudio $Estudio
 * @property PaginatorComponent $Paginator
 */
class EstudiosController extends AppController {

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
		$this->Estudio->recursive = 0;
		$this->set('estudios', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Estudio->exists($id)) {
			throw new NotFoundException(__('Invalid estudio'));
		}

		$options = array('conditions' => array('Estudio.' . $this->Estudio->primaryKey => $id));
		$this->set('estudio', $this->Estudio->find('first', $options));
		$this->request->data = $this->Estudio->find('first', $options);

	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Estudio->create();
			if ($this->Estudio->save($this->request->data)) {
				$this->Session->setFlash(__('The estudio has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The estudio could not be saved. Please, try again.'));
			}
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
		if (!$this->Estudio->exists($id)) {
			throw new NotFoundException(__('Invalid estudio'));
		}
		if ($this->request->is('post')) {
			$inicioactividades =$this->request->data['Estudio']['inicioactividades'];
			$this->request->data('Estudio.inicioactividades',date('Y-m-d',strtotime($inicioactividades)));
			if ($this->Estudio->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos del estudio se han guardado.'));
				return $this->redirect(array('action' => 'view',$id));
			} else {
				$this->Session->setFlash(__('No se pudieron guardar los datos del estudio por favor intente de nuevo mas tarde'));
			}
		} else {
			$options = array('conditions' => array('Estudio.' . $this->Estudio->primaryKey => $id));
			$this->request->data = $this->Estudio->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Estudio->id = $id;
		if (!$this->Estudio->exists()) {
			throw new NotFoundException(__('Invalid estudio'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Estudio->delete()) {
			$this->Session->setFlash(__('The estudio has been deleted.'));
		} else {
			$this->Session->setFlash(__('The estudio could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function superadminestudioadd() 
	{		
		if ($this->request->is('post')) 
		{			
			$this->Estudio->create();

			$EtudioNombre = $this->request->data['Estudio']['nombre'];
			$PrimerUsuario = $this->request->data['Estudio']['usuario'];
			$PassPrimerUsuario = $this->request->data['Estudio']['password'];
			$EstudioEmail = $this->request->data['Estudio']['email'];

			if ($this->Estudio->save($this->request->data)) 
			{
				$EstudioId = $this->Estudio->getLastInsertID();
				$this->loadModel('User');
				$this->User->create();
				$this->User->set('estudio_id', $EstudioId);
				$this->User->set('mail', $EstudioEmail);
				$this->User->set('nombre', $EtudioNombre);
				$this->User->set('username',$PrimerUsuario);
				//$RandomPass = 'Conta2017_'.rand(1,4);
				$this->User->set('password',$PassPrimerUsuario); 
				$this->User->set('tipo','administrador'); 
				$this->User->set('etado','habilitado'); 
				$this->User->save();
				$UserInertedId = $this->User->getLastInsertID();

				//cargar la tareas.
				$this->loadModel('Tareascliente');
				$tareasclientesOpciones = array(
				    'conditions' => array('Tareascliente.estado' => 'habilitado'), 
				    'fields' => array('Tareascliente.id','Tareascliente.orden','Tareascliente.descripcion', 'Tareascliente.tipo')
				    //'group' => 'Deposito.id'
				    );
				$tareascliente = $this->Tareascliente->find('all',$tareasclientesOpciones);
				$this->loadModel('Tareasxclientesxestudio');
				
				foreach ($tareascliente as $tareacliente) {
					$this->Tareasxclientesxestudio->create();
					$this->Tareasxclientesxestudio->set('orden',$tareacliente['Tareascliente']['orden']); 
					$this->Tareasxclientesxestudio->set('descripcion',$tareacliente['Tareascliente']['descripcion']); 
					$this->Tareasxclientesxestudio->set('tareascliente_id',$tareacliente['Tareascliente']['id']); 
					$this->Tareasxclientesxestudio->set('estado','habilitado'); 
					$this->Tareasxclientesxestudio->set('tipo',$tareacliente['Tareascliente']['tipo']); 
					$this->Tareasxclientesxestudio->set('estudio_id',$EstudioId);
					$this->Tareasxclientesxestudio->set('user_id', $UserInertedId);
					$this->Tareasxclientesxestudio->save();
				}

				$this->Session->setFlash(__('El Etudio se ha registrado con exito.'));
				return $this->redirect(array('controller' => 'superadmins',
											 'action' => 'index'));
			} 
			else 
			{
				$this->Session->setFlash(__('No se pudo registrar el Estudio, intente más tarde.'));
			}
		}
		//$estudios = $this->User->Estudio->find('list');
		//$this->set(compact('estudios'));
	}
}
