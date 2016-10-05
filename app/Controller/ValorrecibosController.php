<?php
App::uses('AppController', 'Controller');
/**
 * Valorrecibos Controller
 *
 * @property Valorrecibo $Valorrecibo
 * @property PaginatorComponent $Paginator
 */
class ValorrecibosController extends AppController {

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
		$this->Valorrecibo->recursive = 0;
		$this->set('valorrecibos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Valorrecibo->exists($id)) {
			throw new NotFoundException(__('Invalid valorrecibo'));
		}
		$options = array('conditions' => array('Valorrecibo.' . $this->Valorrecibo->primaryKey => $id));
		$this->set('valorrecibo', $this->Valorrecibo->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Valorrecibo->create();
			if ($this->Valorrecibo->save($this->request->data)) {
				$this->Session->setFlash(__('The valorrecibo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The valorrecibo could not be saved. Please, try again.'));
			}
		}
		$cctxconceptos = $this->Valorrecibo->Cctxconcepto->find('list');
		$empleados = $this->Valorrecibo->Empleado->find('list');
		$this->set(compact('cctxconceptos', 'empleados'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Valorrecibo->exists($id)) {
			throw new NotFoundException(__('Invalid valorrecibo'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Valorrecibo->save($this->request->data)) {
				$this->Session->setFlash(__('The valorrecibo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The valorrecibo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Valorrecibo.' . $this->Valorrecibo->primaryKey => $id));
			$this->request->data = $this->Valorrecibo->find('first', $options);
		}
		$cctxconceptos = $this->Valorrecibo->Cctxconcepto->find('list');
		$empleados = $this->Valorrecibo->Empleado->find('list');
		$this->set(compact('cctxconceptos', 'empleados'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Valorrecibo->id = $id;
		if (!$this->Valorrecibo->exists()) {
			throw new NotFoundException(__('Invalid valorrecibo'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Valorrecibo->delete()) {
			$this->Session->setFlash(__('The valorrecibo has been deleted.'));
		} else {
			$this->Session->setFlash(__('The valorrecibo could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
