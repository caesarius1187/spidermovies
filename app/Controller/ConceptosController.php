<?php
App::uses('AppController', 'Controller');
/**
 * Conceptos Controller
 *
 * @property Concepto $Concepto
 * @property PaginatorComponent $Paginator
 */
class ConceptosController extends AppController {

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
		$this->Concepto->recursive = 0;
		$this->set('conceptos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Concepto->exists($id)) {
			throw new NotFoundException(__('Invalid concepto'));
		}
		$options = array('conditions' => array('Concepto.' . $this->Concepto->primaryKey => $id));
		$this->set('concepto', $this->Concepto->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Concepto->create();
			if ($this->Concepto->save($this->request->data)) {
				return $this->flash(__('The concepto has been saved.'), array('action' => 'index'));
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
		if (!$this->Concepto->exists($id)) {
			throw new NotFoundException(__('Invalid concepto'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Concepto->save($this->request->data)) {
				return $this->flash(__('The concepto has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('Concepto.' . $this->Concepto->primaryKey => $id));
			$this->request->data = $this->Concepto->find('first', $options);
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
		$this->Concepto->id = $id;
		if (!$this->Concepto->exists()) {
			throw new NotFoundException(__('Invalid concepto'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Concepto->delete()) {
			return $this->flash(__('The concepto has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The concepto could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}}
