<?php
App::uses('AppController', 'Controller');
/**
 * Eventos Controller
 *
 * @property Evento $Evento
 * @property PaginatorComponent $Paginator
 */
class EventosController extends AppController {

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
		$this->Evento->recursive = 0;
		$this->set('eventos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Evento->exists($id)) {
			throw new NotFoundException(__('Invalid evento'));
		}
		$options = array('conditions' => array('Evento.' . $this->Evento->primaryKey => $id));
		$this->set('evento', $this->Evento->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		/*if ($this->request->is('post')) {
			$this->Evento->create();
			if ($this->Evento->save($this->request->data)) {
				$this->Session->setFlash(__('The evento has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The evento could not be saved. Please, try again.'));
			}
		}
		$users = $this->Evento->User->find('list');
		$lugarpagos = $this->Evento->Lugarpago->find('list');
		$impclis = $this->Evento->Impcli->find('list');
		$this->set(compact('users', 'lugarpagos', 'impclis'));*/
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Evento->exists($id)) {
			throw new NotFoundException(__('Invalid evento'));
		}
		if ($this->request->is('post')) {
			if ($this->Evento->save($this->request->data)) {
				$this->Session->setFlash(__('The evento has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The evento could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Evento.' . $this->Evento->primaryKey => $id));
			$this->request->data = $this->Evento->find('first', $options);
		}
		$users = $this->Evento->User->find('list');
		$lugarpagos = $this->Evento->Lugarpago->find('list');
		$impclis = $this->Evento->Impcli->find('list');
		$this->set(compact('users', 'lugarpagos', 'impclis'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Evento->id = $id;
		if (!$this->Evento->exists()) {
			throw new NotFoundException(__('Invalid evento'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Evento->delete()) {
			$this->Session->setFlash(__('The evento has been deleted.'));
		} else {
			$this->Session->setFlash(__('The evento could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
