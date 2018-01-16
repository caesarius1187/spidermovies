<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','Auth');

/**
 * index method
 *
 * @return void
 */
	
	public function beforeFilter() {
    	parent::beforeFilter();
    	$this->Auth->allow('logout');
	}

    public function login() {
    	$this->layout="default_login";

	    if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
	            return $this->redirect($this->Auth->redirectUrl());
	        }
	        $this->Session->setFlash(__('Nombre de usuario o contrase&ncaron;a inv&aacute;lida.'));
	    }
	}

	public function logout() {
	    return $this->redirect($this->Auth->logout());
	}

	public function index() {
		$this->User->recursive = 0;
		$conditionsUsu = array('User.estudio_id' => $this->Session->read('Auth.User.estudio_id'),);
		$users = $this->User->find('all',array('conditions' =>$conditionsUsu));
		$this->set('users', $users);

	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Su usuario ha sido registrado.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('No se pudo registrar, intente m&aacute;s tarde.'));
			}
		}
		$estudios = $this->User->Estudio->find('list');
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
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('El usuario se ha guardado con exito.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$estudios = $this->User->Estudio->find('list');
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	public function editajax($UsuarioId=null) 
	{
		if (!$this->User->exists($UsuarioId)) {
			throw new NotFoundException(__('Usuario invalido'));
		}
		
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $UsuarioId));
		$this->request->data = $this->User->find('first', $options);

		//$optionsCli = array('conditions' => array('Cliente.id' => $cliid));
		//$clientes = $this->Domicilio->Cliente->find('list', $optionsCli);

		//$localidades = $this->Domicilio->Localidade->find('list');
		//$this->set(compact('clientes', 'localidades'));

		//$data = array('conditions' => array('User.' . $this->User->primaryKey => $UsuarioId));
		//$this->set('data', $this->User->find('first', $data));

		//$data = array(
	    //        "respuesta" => "El Grupo ha sido guardado con exito.",
	    //        "Grupocliente_id" => $this->Grupocliente->getLastInsertID()
	    //    );
	    //    $this->set('data', $data);

		$this->layout = 'ajax';
		//$this->render('serializejson');	
		$this->render('edit');	
	}
}