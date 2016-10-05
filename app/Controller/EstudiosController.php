<?php
App::uses('AppController', 'Controller');
/**
 * Estudios Controller
 *
 * @property Estudio $Estudio
 * @property PaginatorComponent $Paginator
 */
class EstudiosController extends AppController {

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
		$this->Estudio->recursive = 0;
		$this->set('estudios', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Estudio->exists($id)) {
			throw new NotFoundException(__('Invalid estudio'));
		}
		$options = array('conditions' => array('Estudio.' . $this->Estudio->primaryKey => $id));
		$this->set('estudio', $this->Estudio->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Estudio->create();
			if ($this->Estudio->save($this->request->data)) {
				$this->Session->setFlash(__('The estudio has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The estudio could not be saved. Please, try again.'));
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
		if (!$this->Estudio->exists($id)) {
			throw new NotFoundException(__('Invalid estudio'));
		}
		if ($this->request->is('post')) {
			if ($this->Estudio->save($this->request->data)) {
				$this->Session->setFlash(__('The estudio has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The estudio could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Estudio.' . $this->Estudio->primaryKey => $id));
			$this->request->data = $this->Estudio->find('first', $options);
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
		$this->Estudio->id = $id;
		if (!$this->Estudio->exists()) {
			throw new NotFoundException(__('Invalid estudio'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Estudio->delete()) {
			$this->Session->setFlash(__('The estudio has been deleted.'));
		} else {
			$this->Session->setFlash(__('The estudio could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
