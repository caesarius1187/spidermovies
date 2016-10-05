<?php
App::uses('AppController', 'Controller');
/**
 * Grupoclientes Controller
 *
 * @property Grupocliente $Grupocliente
 * @property PaginatorComponent $Paginator
 */
class GrupoclientesController extends AppController {

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
		$this->Grupocliente->recursive = 0;

		$conditionsGcli = array('Grupocliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),);
		$grupoclientes = $this->Grupocliente->find('all',array('conditions' =>$conditionsGcli));
		$this->set('grupoclientes', $grupoclientes);

	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Grupocliente->exists($id)) {
			throw new NotFoundException(__('Invalid grupocliente'));
		}
		$options = array('conditions' => array('Grupocliente.' . $this->Grupocliente->primaryKey => $id));
		$this->set('grupocliente', $this->Grupocliente->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Grupocliente->create();
			if ($this->Grupocliente->save($this->request->data)) {
				$this->Session->setFlash(__('El Grupo de Clientes se guardo con exito.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The grupocliente could not be saved. Please, try again.'));
			}
		}
		$estudios = $this->Grupocliente->Estudio->find('list');
		$this->set(compact('estudios'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Grupocliente->exists($id)) {
			throw new NotFoundException(__('Invalid grupocliente'));
		}
		if ($this->request->is('post')) {
			if ($this->Grupocliente->save($this->request->data)) {
				$this->Session->setFlash(__('El grupo de clientes se modifico con exito.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The grupocliente could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Grupocliente.' . $this->Grupocliente->primaryKey => $id));
			$this->request->data = $this->Grupocliente->find('first', $options);
		}
		$estudios = $this->Grupocliente->Estudio->find('list');
		$this->set(compact('estudios'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Grupocliente->id = $id;
		if (!$this->Grupocliente->exists()) {
			throw new NotFoundException(__('Invalid grupocliente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Grupocliente->delete()) {
			$this->Session->setFlash(__('El grupo de clientes ha sido eliminado.'));
		} else {
			$this->Session->setFlash(__('The grupocliente could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function editajax($GrupoId = null) 
	{
		if (!$this->Grupocliente->exists($GrupoId)) {
			throw new NotFoundException(__('Invalid direccione'));
		}
		
		$options = array('conditions' => array('Grupocliente.' . $this->Grupocliente->primaryKey => $GrupoId));
		$this->request->data = $this->Grupocliente->find('first', $options);
		


		$this->layout = 'ajax';
		$this->render('edit');	
	}

	/*
	public function addGrupoCliente($nomGrupo = null,$descGrupo = null,$estado = null,$estudioId = null) {

	 	$this->request->onlyAllow('ajax');

		$this->Grupocliente->create();
		$this->Grupocliente->set('nombre',$nomGrupo);
		$this->Grupocliente->set('descripcion',$descGrupo);
		$this->Grupocliente->set('estado',$estado);
		$this->Grupocliente->set('estudio_id',$estudioId);

		if ($this->Grupocliente->save($this->request->data)) 
		{
			//$this->set('respuesta','El Grupo ha sido creado.');	
			//$this->set('Grupocliente_id',$this->Grupocliente->getLastInsertID());			
			$data = array(
	            "respuesta" => "El Grupo ha sido guardado con exito.",
	            "Grupocliente_id" => $this->Grupocliente->getLastInsertID()
	        );
	        $this->set('data', $data);
		}
		else
		{
			$data = array(
	            "respuesta" => "error"	            
	        );
	        $this->set('data', $data);
		}
		$this->layout = 'ajax';
		//$this->render('addajax');
		//$this->render('/Grupoclientes/SerializeJson/');
		$this->render('serializejson');
	}
	*/
}