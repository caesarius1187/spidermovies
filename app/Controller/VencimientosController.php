<?php
App::uses('AppController', 'Controller');
/**
 * Vencimientos Controller
 *
 * @property Vencimiento $Vencimiento
 * @property PaginatorComponent $Paginator
 */
class VencimientosController extends AppController {

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
		$this->Vencimiento->recursive = 0;
		$this->set('vencimientos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Vencimiento->exists($id)) {
			throw new NotFoundException(__('Invalid vencimiento'));
		}
		$options = array('conditions' => array('Vencimiento.' . $this->Vencimiento->primaryKey => $id));
		$this->set('vencimiento', $this->Vencimiento->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Vencimiento->create();
			if ($this->Vencimiento->saveAll($this->request->data['Vencimiento'])) {
				$this->Session->setFlash(__('Los vencimientos han sido guardados.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar vencimientos, por favor intende de nuevo mas tarde.'));
			}
		}
		$impuestos = $this->Vencimiento->Impuesto->find('list');
		$this->set(compact('impuestos'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Vencimiento->exists($id)) {
			throw new NotFoundException(__('Invalid vencimiento'));
		}
		if ($this->request->is('post')) {
			if ($this->Vencimiento->save($this->request->data)) {
				$this->Session->setFlash(__('The vencimiento has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The vencimiento could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Vencimiento.' . $this->Vencimiento->primaryKey => $id));
			$this->request->data = $this->Vencimiento->find('first', $options);
		}
		$impuestos = $this->Vencimiento->Impuesto->find('list');
		$this->set(compact('impuestos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Vencimiento->id = $id;
		if (!$this->Vencimiento->exists()) {
			throw new NotFoundException(__('Invalid vencimiento'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Vencimiento->delete()) {
			$this->Session->setFlash(__('The vencimiento has been deleted.'));
		} else {
			$this->Session->setFlash(__('The vencimiento could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
