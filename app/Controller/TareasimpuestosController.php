<?php
App::uses('AppController', 'Controller');
/**
 * Tareasimpuestos Controller
 *
 * @property Tareasimpuesto $Tareasimpuesto
 * @property PaginatorComponent $Paginator
 */
class TareasimpuestosController extends AppController {

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
		$this->Tareasimpuesto->recursive = 0;
		$this->set('tareasimpuestos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Tareasimpuesto->exists($id)) {
			throw new NotFoundException(__('Invalid tareasimpuesto'));
		}
		$options = array('conditions' => array('Tareasimpuesto.' . $this->Tareasimpuesto->primaryKey => $id));
		$this->set('tareasimpuesto', $this->Tareasimpuesto->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Tareasimpuesto->create();
			if ($this->Tareasimpuesto->save($this->request->data)) {
				$this->Session->setFlash(__('The tareasimpuesto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tareasimpuesto could not be saved. Please, try again.'));
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
		if (!$this->Tareasimpuesto->exists($id)) {
			throw new NotFoundException(__('Invalid tareasimpuesto'));
		}
		if ($this->request->is('post')) {
			if ($this->Tareasimpuesto->save($this->request->data)) {
				$this->Session->setFlash(__('The tareasimpuesto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tareasimpuesto could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Tareasimpuesto.' . $this->Tareasimpuesto->primaryKey => $id));
			$this->request->data = $this->Tareasimpuesto->find('first', $options);
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
		$this->Tareasimpuesto->id = $id;
		if (!$this->Tareasimpuesto->exists()) {
			throw new NotFoundException(__('Invalid tareasimpuesto'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Tareasimpuesto->delete()) {
			$this->Session->setFlash(__('The tareasimpuesto has been deleted.'));
		} else {
			$this->Session->setFlash(__('The tareasimpuesto could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
