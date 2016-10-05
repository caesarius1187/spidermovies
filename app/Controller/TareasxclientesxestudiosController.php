<?php
App::uses('AppController', 'Controller');
/**
 * Tareasxclientesxestudios Controller
 *
 * @property Tareasxclientesxestudio $Tareasxclientesxestudio
 * @property PaginatorComponent $Paginator
 */
class TareasxclientesxestudiosController extends AppController {

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
	
		$this->Tareasxclientesxestudio->recursive = 0;

		$conditionsTareas = array('Tareasxclientesxestudio.estudio_id' => $this->Session->read('Auth.User.estudio_id'),);
		$tareasxclientesxestudios = $this->Tareasxclientesxestudio->find('all',array('conditions' =>$conditionsTareas));
		$this->set('tareasxclientesxestudios', $tareasxclientesxestudios);

		$conditionsUsers = array('User.estudio_id' => $this->Session->read('Auth.User.estudio_id'),);
		$users = $this->Tareasxclientesxestudio->User->find('list',array('conditions' =>$conditionsUsers));
		$this->set(compact('users'));

	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Tareasxclientesxestudio->exists($id)) {
			throw new NotFoundException(__('Invalid tareasxclientesxestudio'));
		}
		$options = array('conditions' => array('Tareasxclientesxestudio.' . $this->Tareasxclientesxestudio->primaryKey => $id));
		$this->set('tareasxclientesxestudio', $this->Tareasxclientesxestudio->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Tareasxclientesxestudio->create();
			if ($this->Tareasxclientesxestudio->save($this->request->data)) {
				$this->Session->setFlash(__('The tareasxclientesxestudio has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tareasxclientesxestudio could not be saved. Please, try again.'));
			}
		}
		$tareasclientes = $this->Tareasxclientesxestudio->Tareascliente->find('list');
		$estudios = $this->Tareasxclientesxestudio->Estudio->find('list');
		$this->set(compact('tareasclientes', 'estudios'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Tareasxclientesxestudio->exists($id)) {
			throw new NotFoundException(__('Invalid tareasxclientesxestudio'));
		}
		if ($this->request->is('post')) {
			if ($this->Tareasxclientesxestudio->save($this->request->data)) {
				$this->Session->setFlash(__('The tareasxclientesxestudio has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tareasxclientesxestudio could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Tareasxclientesxestudio.' . $this->Tareasxclientesxestudio->primaryKey => $id));
			$this->request->data = $this->Tareasxclientesxestudio->find('first', $options);
		}
		$tareasclientes = $this->Tareasxclientesxestudio->Tareascliente->find('list');
		$estudios = $this->Tareasxclientesxestudio->Estudio->find('list');
		$this->set(compact('tareasclientes', 'estudios'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Tareasxclientesxestudio->id = $id;
		if (!$this->Tareasxclientesxestudio->exists()) {
			throw new NotFoundException(__('Invalid tareasxclientesxestudio'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Tareasxclientesxestudio->delete()) {
			$this->Session->setFlash(__('La tarea ha sido eliminada.'));
		} else {
			$this->Session->setFlash(__('La tarea no ha sido eliminada. Por favor, pruebe de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function cambiarestado($id = null, $estadoactual) {
		$this->Tareasxclientesxestudio->id = $id;
		if (!$this->Tareasxclientesxestudio->exists()) {
			throw new NotFoundException(__('Invalid tareasxclientesxestudio'));
		}
		if ($estadoactual == "deshabilitado"){
		$this->Tareasxclientesxestudio->set(array(
      		'estado' => 'habilitado',
		));
		} else {
			$this->Tareasxclientesxestudio->set(array(
      		'estado' => 'deshabilitado',
		));
		}
		if ($this->Tareasxclientesxestudio->save()) {
			$this->Session->setFlash(__('La tarea cambio de estado.'));
		} else {
			$this->Session->setFlash(__('La tarea NO cambio de estado. Por favor, pruebe de nuevo.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function modificarResponsable($data=null) {
	
		if ($this->request->is('post')) {
			$id=$this->request->data['tareasxclientesxestudio']['idtarea'];
			$userid=$this->request->data['tareasxclientesxestudio']['users'];
			if (!$this->Tareasxclientesxestudio->exists($id)) {
				$this->Session->setFlash(__('La tarea NO cambio de responsable. Por favor, pruebe de nuevo.'));

			}else{
				$this->Tareasxclientesxestudio->read(null, $id);
				$this->Tareasxclientesxestudio->set('user_id',$userid);
				if ($this->Tareasxclientesxestudio->save()) {
					$this->Session->setFlash(__('La tarea cambio de responsable.'));

				}
			}
		}
		return $this->redirect(array('action' => 'index'));
	}
public function isAuthorized($user) {
	    // All registered users can add posts
	    if ($this->action === 'add') {
	        return true;
	    }

	    // The owner of a post can edit and delete it
	    if (in_array($this->action, array('edit', 'delete'))) {
	        $postId = (int) $this->request->params['pass'][0];
	        if ($this->Post->isOwnedBy($postId, $user['id'])) {
	            return true;
	        }
	    }

	    return parent::isAuthorized($user);
	}

}