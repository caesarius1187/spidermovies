<?php
App::uses('AppController', 'Controller');
/**
 * Impuestos Controller
 *
 * @property Impuesto $Impuesto
 * @property PaginatorComponent $Paginator
 */
class ImpuestosController extends AppController {

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
		$this->Impuesto->recursive = 0;
		$this->set('impuestos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Impuesto->exists($id)) {
			throw new NotFoundException(__('Invalid impuesto'));
		}
		$options = array('conditions' => array('Impuesto.' . $this->Impuesto->primaryKey => $id));
		$this->set('impuesto', $this->Impuesto->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Impuesto->create();
			if ($this->Impuesto->save($this->request->data)) {
				$this->Session->setFlash(__('The impuesto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The impuesto could not be saved. Please, try again.'));
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
		if (!$this->Impuesto->exists($id)) {
			throw new NotFoundException(__('Invalid impuesto'));
		}
		if ($this->request->is('post')) {
			if ($this->Impuesto->save($this->request->data)) {
				$this->Session->setFlash(__('The impuesto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The impuesto could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Impuesto.' . $this->Impuesto->primaryKey => $id));
			$this->request->data = $this->Impuesto->find('first', $options);
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
		$this->Impuesto->id = $id;
		if (!$this->Impuesto->exists()) {
			throw new NotFoundException(__('Invalid impuesto'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Impuesto->delete()) {
			$this->Session->setFlash(__('The impuesto has been deleted.'));
		} else {
			$this->Session->setFlash(__('The impuesto could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
