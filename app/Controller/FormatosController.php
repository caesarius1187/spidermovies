<?php
App::uses('AppController', 'Controller');
/**
 * Formatos Controller
 *
 * @property Formato $Formato
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class FormatosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Formato->recursive = 0;
		$this->set('formatos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Formato->exists($id)) {
			throw new NotFoundException(__('Invalid formato'));
		}
		$options = array('conditions' => array('Formato.' . $this->Formato->primaryKey => $id));
		$this->set('formato', $this->Formato->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Formato->create();
			if ($this->Formato->save($this->request->data)) {
				$this->Session->setFlash(__('The formato has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The formato could not be saved. Please, try again.'));
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
		if (!$this->Formato->exists($id)) {
			throw new NotFoundException(__('Invalid formato'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Formato->save($this->request->data)) {
				$this->Session->setFlash(__('The formato has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The formato could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Formato.' . $this->Formato->primaryKey => $id));
			$this->request->data = $this->Formato->find('first', $options);
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
		$this->Formato->id = $id;
		if (!$this->Formato->exists()) {
			throw new NotFoundException(__('Invalid formato'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Formato->delete()) {
			$this->Session->setFlash(__('The formato has been deleted.'));
		} else {
			$this->Session->setFlash(__('The formato could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
