<?php
App::uses('AppController', 'Controller');
/**
 * Tareasclientes Controller
 *
 * @property Tareascliente $Tareascliente
 * @property PaginatorComponent $Paginator
 */
class TareasclientesController extends AppController {

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
		$this->Tareascliente->recursive = 0;

		$conditionsTarea = array('Tareascliente.estudio_id' => $this->Session->read('Auth.User.estudio_id'),);
		$tareasclientes = $this->Tareascliente->find('all',array('conditions' =>$conditionsTarea));
		$this->set('tareasclientes', $tareasclientes);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Tareascliente->exists($id)) {
			throw new NotFoundException(__('Invalid tareascliente'));
		}
		$options = array('conditions' => array('Tareascliente.' . $this->Tareascliente->primaryKey => $id));
		$this->set('tareascliente', $this->Tareascliente->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Tareascliente->create();
			if ($this->Tareascliente->save($this->request->data)) {
				$this->Session->setFlash(__('The tareascliente has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tareascliente could not be saved. Please, try again.'));
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
		if (!$this->Tareascliente->exists($id)) {
			throw new NotFoundException(__('Invalid tareascliente'));
		}
		if ($this->request->is('post')) {
			if ($this->Tareascliente->save($this->request->data)) {
				$this->Session->setFlash(__('The tareascliente has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tareascliente could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Tareascliente.' . $this->Tareascliente->primaryKey => $id));
			$this->request->data = $this->Tareascliente->find('first', $options);
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
		$this->Tareascliente->id = $id;
		if (!$this->Tareascliente->exists()) {
			throw new NotFoundException(__('Invalid tareascliente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Tareascliente->delete()) {
			$this->Session->setFlash(__('The tareascliente has been deleted.'));
		} else {
			$this->Session->setFlash(__('The tareascliente could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
