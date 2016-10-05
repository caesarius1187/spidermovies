<?php
App::uses('AppController', 'Controller');
/**
 * Lugarpagos Controller
 *
 * @property Lugarpago $Lugarpago
 * @property PaginatorComponent $Paginator
 */
class LugarpagosController extends AppController {

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
		$this->Lugarpago->recursive = 0;
		$this->set('lugarpagos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Lugarpago->exists($id)) {
			throw new NotFoundException(__('Invalid lugarpago'));
		}
		$options = array('conditions' => array('Lugarpago.' . $this->Lugarpago->primaryKey => $id));
		$this->set('lugarpago', $this->Lugarpago->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Lugarpago->create();
			if ($this->Lugarpago->save($this->request->data)) {
				$this->Session->setFlash(__('The lugarpago has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The lugarpago could not be saved. Please, try again.'));
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
		if (!$this->Lugarpago->exists($id)) {
			throw new NotFoundException(__('Invalid lugarpago'));
		}
		if ($this->request->is('post')) {
			if ($this->Lugarpago->save($this->request->data)) {
				$this->Session->setFlash(__('The lugarpago has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The lugarpago could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Lugarpago.' . $this->Lugarpago->primaryKey => $id));
			$this->request->data = $this->Lugarpago->find('first', $options);
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
		$this->Lugarpago->id = $id;
		if (!$this->Lugarpago->exists()) {
			throw new NotFoundException(__('Invalid lugarpago'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Lugarpago->delete()) {
			$this->Session->setFlash(__('The lugarpago has been deleted.'));
		} else {
			$this->Session->setFlash(__('The lugarpago could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
