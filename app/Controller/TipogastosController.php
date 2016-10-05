<?php
App::uses('AppController', 'Controller');
/**
 * Tipogastos Controller
 *
 * @property Tipogasto $Tipogasto
 * @property PaginatorComponent $Paginator
 */
class TipogastosController extends AppController {

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
		$this->Tipogasto->recursive = 0;
		$this->set('tipogastos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Tipogasto->exists($id)) {
			throw new NotFoundException(__('Invalid tipogasto'));
		}
		$options = array('conditions' => array('Tipogasto.' . $this->Tipogasto->primaryKey => $id));
		$this->set('tipogasto', $this->Tipogasto->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Tipogasto->create();
			if ($this->Tipogasto->save($this->request->data)) {
				$this->Session->setFlash(__('The tipogasto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tipogasto could not be saved. Please, try again.'));
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
		if (!$this->Tipogasto->exists($id)) {
			throw new NotFoundException(__('Invalid tipogasto'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Tipogasto->save($this->request->data)) {
				$this->Session->setFlash(__('The tipogasto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tipogasto could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Tipogasto.' . $this->Tipogasto->primaryKey => $id));
			$this->request->data = $this->Tipogasto->find('first', $options);
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
		$this->Tipogasto->id = $id;
		if (!$this->Tipogasto->exists()) {
			throw new NotFoundException(__('Invalid tipogasto'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Tipogasto->delete()) {
			$this->Session->setFlash(__('The tipogasto has been deleted.'));
		} else {
			$this->Session->setFlash(__('The tipogasto could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
