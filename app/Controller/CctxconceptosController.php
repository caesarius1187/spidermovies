<?php
App::uses('AppController', 'Controller');
/**
 * Cctxconceptos Controller
 *
 * @property Cctxconcepto $Cctxconcepto
 * @property PaginatorComponent $Paginator
 */
class CctxconceptosController extends AppController {

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
		$this->Cctxconcepto->recursive = 0;
		$this->set('cctxconceptos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Cctxconcepto->exists($id)) {
			throw new NotFoundException(__('Invalid cctxconcepto'));
		}
		$options = array('conditions' => array('Cctxconcepto.' . $this->Cctxconcepto->primaryKey => $id));
		$this->set('cctxconcepto', $this->Cctxconcepto->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Cctxconcepto->create();
			if ($this->Cctxconcepto->save($this->request->data)) {
				$this->Session->setFlash(__('The cctxconcepto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cctxconcepto could not be saved. Please, try again.'));
			}
		}
		$conveniocolectivotrabajos = $this->Cctxconcepto->Conveniocolectivotrabajo->find('list');
		$conceptos = $this->Cctxconcepto->Concepto->find('list');
		$this->set(compact('conveniocolectivotrabajos', 'conceptos'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Cctxconcepto->exists($id)) {
			throw new NotFoundException(__('Invalid cctxconcepto'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Cctxconcepto->save($this->request->data)) {
				$this->Session->setFlash(__('The cctxconcepto has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cctxconcepto could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Cctxconcepto.' . $this->Cctxconcepto->primaryKey => $id));
			$this->request->data = $this->Cctxconcepto->find('first', $options);
		}
		$conveniocolectivotrabajos = $this->Cctxconcepto->Conveniocolectivotrabajo->find('list');
		$conceptos = $this->Cctxconcepto->Concepto->find('list');
		$this->set(compact('conveniocolectivotrabajos', 'conceptos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Cctxconcepto->id = $id;
		if (!$this->Cctxconcepto->exists()) {
			throw new NotFoundException(__('Invalid cctxconcepto'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cctxconcepto->delete()) {
			$this->Session->setFlash(__('The cctxconcepto has been deleted.'));
		} else {
			$this->Session->setFlash(__('The cctxconcepto could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
